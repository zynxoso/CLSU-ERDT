<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use App\Models\FacultyMember;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditService;

use App\Models\SiteSetting;
use App\Services\SystemConfigService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SuperAdminController extends Controller
{
    private $auditService;

    /**
     * Create a new controller instance.
     *
     * @param AuditService $auditService
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
        $this->auditService = $auditService;
    }

    /**
     * Show the super admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Since we're using Livewire, we just need to return the view with the component
        // All data loading is now handled by the Livewire component
        return view('super_admin.dashboard-livewire');
    }

    /**
     * Show the super admin profile edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('super_admin.profile.edit', compact('user'));
    }

    /**
     * Update the super admin profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $this->auditService->log('profile_updated', 'User', $user->id,
            'Updated profile - Name: ' . $request->name . ', Email: ' . $request->email
        );

        return redirect()->route('super_admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the change password form for super admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChangePasswordForm()
    {
        return view('super_admin.password.change');
    }

    /**
     * Change the super admin password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Check if password change was mandatory before updating
        $wasMandatory = $user->must_change_password;

        // Get dynamic password expiry days from settings
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        $user->update([
            'password' => Hash::make($request->password),
            'is_default_password' => false,
            'must_change_password' => false,
        ]);

        // Set password expiration using dynamic settings
        $user->setPasswordExpiration();

        // Clear session warning flag
        session()->forget('password_expiry_warning_shown');
        session()->forget('show_password_modal');

        $this->auditService->log('password_changed', 'User', $user->id);

        // Determine redirect location based on context
        $redirectRoute = 'super_admin.dashboard';

        // If user came from profile edit page, redirect back there (unless it was mandatory)
        if (!$wasMandatory && request()->headers->get('referer') && str_contains(request()->headers->get('referer'), 'profile/edit')) {
            $redirectRoute = 'super_admin.profile.edit';
        }

        return redirect()->route($redirectRoute)
            ->with('success', "Password changed successfully. Your password will expire in {$passwordExpiryDays} days.");
    }

    /**
     * Show the user management page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userManagement(Request $request)
    {
        return view('super_admin.user_management');
    }

    /**
     * Show the system settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function systemSettings()
    {
        return view('super_admin.system_settings');
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request)
    {
        try {
            $request->validate([
                'site_name' => 'required|string|max:255',
                'site_description' => 'required|string|max:1000',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
            ]);

            $settings = [
                'site_name' => ['value' => $request->site_name, 'type' => 'string'],
                'site_description' => ['value' => $request->site_description, 'type' => 'string'],
            ];

            if ($request->filled('contact_email')) {
                $settings['contact_email'] = ['value' => $request->contact_email, 'type' => 'string'];
            }

            if ($request->filled('contact_phone')) {
                $settings['contact_phone'] = ['value' => $request->contact_phone, 'type' => 'string'];
            }

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'general');
            }

            $this->auditService->log('general_settings_updated', 'System Settings', null,
                'Updated general settings');

            return redirect()->route('super_admin.system_settings')
                ->with('success', 'General settings updated successfully!');

        } catch (ValidationException $e) {
            return redirect()->route('super_admin.system_settings')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('super_admin.system_settings')
                ->with('error', 'An error occurred while saving the settings');
        }
    }

    /**
     * Update email settings
     */
    public function updateEmailSettings(Request $request)
    {
        try {
            $request->validate([
                'mail_driver' => 'required|string|in:smtp,sendmail,mailgun',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|integer|min:1|max:65535',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_encryption' => 'nullable|string|in:tls,ssl',
                'mail_from_address' => 'required|email|max:255',
                'mail_from_name' => 'required|string|max:255',
            ]);

            $settings = [
                'mail_driver' => ['value' => $request->mail_driver, 'type' => 'string'],
                'mail_host' => ['value' => $request->mail_host, 'type' => 'string'],
                'mail_port' => ['value' => $request->mail_port, 'type' => 'integer'],
                'mail_encryption' => ['value' => $request->mail_encryption, 'type' => 'string'],
                'mail_from_address' => ['value' => $request->mail_from_address, 'type' => 'string'],
                'mail_from_name' => ['value' => $request->mail_from_name, 'type' => 'string'],
            ];

            if ($request->filled('mail_username')) {
                $settings['mail_username'] = ['value' => $request->mail_username, 'type' => 'string'];
            }

            if ($request->filled('mail_password')) {
                $settings['mail_password'] = ['value' => $request->mail_password, 'type' => 'string'];
            }

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'email');
            }

            $this->auditService->log('email_settings_updated', 'System Settings', null,
                'Updated email settings');

            return redirect()->route('super_admin.system_settings')
                ->with('success', 'Email settings updated successfully!');

        } catch (ValidationException $e) {
            return redirect()->route('super_admin.system_settings')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('super_admin.system_settings')
                ->with('error', 'An error occurred while saving the email settings');
        }
    }

    /**
     * Update security settings
     */
    public function updateSecuritySettings(Request $request)
    {
        try {
            $request->validate([
                'session_lifetime' => 'required|integer|min:5|max:1440',
                'password_expiry_days' => 'required|integer|min:1|max:365',
                'max_login_attempts' => 'required|integer|min:1|max:20',
                'lockout_duration' => 'required|integer|min:1|max:1440',
                'two_factor_auth' => 'nullable|boolean',
                'force_https' => 'nullable|boolean',
            ]);

            $settings = [
                'session_lifetime' => ['value' => $request->session_lifetime, 'type' => 'integer'],
                'password_expiry_days' => ['value' => $request->password_expiry_days, 'type' => 'integer'],
                'max_login_attempts' => ['value' => $request->max_login_attempts, 'type' => 'integer'],
                'lockout_duration' => ['value' => $request->lockout_duration, 'type' => 'integer'],
                'two_factor_auth' => ['value' => $request->has('two_factor_auth'), 'type' => 'boolean'],
                'force_https' => ['value' => $request->has('force_https'), 'type' => 'boolean'],
            ];

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'security');
            }

            $this->auditService->log('security_settings_updated', 'System Settings', null,
                'Updated security settings');

            return redirect()->route('super_admin.system_settings')
                ->with('success', 'Security settings updated successfully!');

        } catch (ValidationException $e) {
            return redirect()->route('super_admin.system_settings')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('super_admin.system_settings')
                ->with('error', 'An error occurred while saving the security settings');
        }
    }

    /**
     * Show the system configuration page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function systemConfiguration()
    {
        $academicSettings = SiteSetting::getByGroup('academic');
        $scholarshipSettings = SiteSetting::getByGroup('scholarship');
        $generalSettings = SiteSetting::getByGroup('general');

        return view('super_admin.system_configuration', compact(
            'academicSettings',
            'scholarshipSettings',
            'generalSettings'
        ));
    }

    /**
     * Update academic calendar configuration
     */
    public function updateAcademicCalendar(Request $request)
    {
        try {
            $request->validate([
                'academic_year' => 'required|string|max:20',
                'first_semester_start' => 'required|date',
                'first_semester_end' => 'required|date|after:first_semester_start',
                'second_semester_start' => 'required|date|after:first_semester_end',
                'second_semester_end' => 'required|date|after:second_semester_start',
                'summer_term_start' => 'nullable|date',
                'summer_term_end' => 'nullable|date|after:summer_term_start',
                'application_deadline_1st' => 'required|date',
                'application_deadline_2nd' => 'required|date',
            ]);

            $settings = [
                'academic_year' => ['value' => $request->academic_year, 'type' => 'string'],
                'first_semester_start' => ['value' => $request->first_semester_start, 'type' => 'date'],
                'first_semester_end' => ['value' => $request->first_semester_end, 'type' => 'date'],
                'second_semester_start' => ['value' => $request->second_semester_start, 'type' => 'date'],
                'second_semester_end' => ['value' => $request->second_semester_end, 'type' => 'date'],
                'application_deadline_1st' => ['value' => $request->application_deadline_1st, 'type' => 'date'],
                'application_deadline_2nd' => ['value' => $request->application_deadline_2nd, 'type' => 'date'],
            ];

            // Add summer term if provided
            if ($request->filled('summer_term_start')) {
                $settings['summer_term_start'] = ['value' => $request->summer_term_start, 'type' => 'date'];
            }
            if ($request->filled('summer_term_end')) {
                $settings['summer_term_end'] = ['value' => $request->summer_term_end, 'type' => 'date'];
            }

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'academic');
            }

            $this->auditService->log('academic_calendar_updated', 'System Configuration', null,
                'Updated academic calendar configuration');

            return response()->json([
                'success' => true,
                'message' => 'Academic calendar configuration saved successfully!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the configuration'
            ], 500);
        }
    }

    /**
     * Update scholarship parameters
     */
    public function updateScholarshipParameters(Request $request)
    {
        try {
            $request->validate([
                'max_monthly_allowance' => 'required|integer|min:0',
                'max_tuition_support' => 'required|integer|min:0',
                'max_research_allowance' => 'required|integer|min:0',
                'max_book_allowance' => 'required|integer|min:0',
                'max_scholarship_duration' => 'required|integer|min:1|max:120',
                'required_documents' => 'nullable|string',
                'require_entrance_exam' => 'nullable|boolean',
                'require_interview' => 'nullable|boolean',
            ]);

            $settings = [
                'max_monthly_allowance' => ['value' => $request->max_monthly_allowance, 'type' => 'integer'],
                'max_tuition_support' => ['value' => $request->max_tuition_support, 'type' => 'integer'],
                'max_research_allowance' => ['value' => $request->max_research_allowance, 'type' => 'integer'],
                'max_book_allowance' => ['value' => $request->max_book_allowance, 'type' => 'integer'],
                'max_scholarship_duration' => ['value' => $request->max_scholarship_duration, 'type' => 'integer'],
                'require_entrance_exam' => ['value' => $request->has('require_entrance_exam'), 'type' => 'boolean'],
                'require_interview' => ['value' => $request->has('require_interview'), 'type' => 'boolean'],
            ];

            if ($request->filled('required_documents')) {
                $settings['required_documents'] = ['value' => $request->required_documents, 'type' => 'array'];
            }

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'scholarship');
            }

            $this->auditService->log('scholarship_parameters_updated', 'System Configuration', null,
                'Updated scholarship parameters');

            return response()->json([
                'success' => true,
                'message' => 'Scholarship parameters saved successfully!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the parameters'
            ], 500);
        }
    }

    /**
     * Show the data management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataManagement()
    {
        return view('super_admin.data_management');
    }



    /**
     * Show the application timeline management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function applicationTimeline()
    {
        return view('super_admin.application_timeline');
    }

    /**
     * Show the form for creating a new timeline item.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createTimelineItem()
    {
        return view('super_admin.application_timeline_create');
    }

    /**
     * Store a newly created timeline item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTimelineItem(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        \App\Models\ApplicationTimeline::create($request->all());

        $this->auditService->log('timeline_created', 'Application Timeline', null,
            'Created new timeline item: ' . $request->activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item created successfully.');
    }

    /**
     * Show the form for editing a timeline item.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editTimelineItem($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        return view('super_admin.application_timeline_edit', compact('timeline'));
    }

    /**
     * Update the specified timeline item.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTimelineItem(Request $request, $id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);

        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        $timeline->update($request->all());

        $this->auditService->log('timeline_updated', 'Application Timeline', $id,
            'Updated timeline item: ' . $timeline->activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item updated successfully.');
    }

    /**
     * Delete the specified timeline item.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTimelineItem($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        $activity = $timeline->activity;

        $timeline->delete();

        $this->auditService->log('timeline_deleted', 'Application Timeline', $id,
            'Deleted timeline item: ' . $activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item deleted successfully.');
    }

    /**
     * Toggle the active status of a timeline item.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleTimelineStatus($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        $timeline->update([
            'is_active' => !$timeline->is_active
        ]);

        $status = $timeline->is_active ? 'activated' : 'deactivated';

        $this->auditService->log('timeline_status_changed', 'Application Timeline', $id,
            'Status changed for timeline item: ' . $timeline->activity . ' - ' . $status);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', "Timeline item {$status} successfully.");
    }

    /**
     * Show the form for editing a user.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('super_admin.edit_user', compact('user'));
    }

    /**
     * Update the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role' => ['required', 'in:admin,super_admin'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // Prevent changing the role of the default super admin
        if ($user->id === 1 && $request->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Cannot change the role of the default super admin.');
        }

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->has('is_active') ? $request->is_active : $user->is_active,
            ]);

            if ($request->has('password') && !empty($request->password)) {
                $request->validate(['password' => ['string', 'min:8', 'confirmed']]);
                $user->update(['password' => Hash::make($request->password)]);
            }

            $this->auditService->log('user_updated', 'User', $user->id,
                'Updated user details for ' . $user->name
            );
            DB::commit();

            return redirect()->route('super_admin.user_management.edit', $user->id)
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update user. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createUser()
    {
        return view('super_admin.create_user');
    }

    /**
     * Store a newly created user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->filled('is_active'),
            ]);

            $this->auditService->log('user_created', 'User', $user->id,
                'Created user ' . $user->name . ' with role ' . $user->role
            );
            DB::commit();

            return redirect()->route('super_admin.user_management')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create user. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified user.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deletion of the current user
        if (Auth::user()->id === $user->id) {
            return redirect()->route('super_admin.user_management')
                ->with('error', 'You cannot delete your own account');
        }

        // Prevent deletion of super admin users by non-super admin users
        if ($user->role === 'super_admin' && Auth::user()->role !== 'super_admin') {
            return redirect()->route('super_admin.user_management')
                ->with('error', 'You cannot delete super admin accounts');
        }

        // Log the user deletion action before deletion
        $this->auditService->log(
            'delete',
            'user',
            $user->id,
            'User deleted: ' . $user->name,
            $user->toArray()
        );

        $user->delete();

        return redirect()->route('super_admin.user_management')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Store a newly created faculty member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFaculty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Map the simplified form fields to the database fields
        $facultyData = [
            'name' => $request->name,
            'position' => $request->degree, // Map degree to position field
            'department' => $request->institution, // Map institution to department field
            'specialization' => $request->specialization,
            'education_background' => $request->degree . ' - ' . $request->specialization, // Combine for education background
            'research_description' => '', // Empty for now
            
            'university_origin' => $request->institution,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('experts'), $fileName);
            $facultyData['photo_path'] = $fileName;
        }

        $faculty = FacultyMember::create($facultyData);

        // Log the action
        $this->auditService->log(
            'create',
            'faculty_member',
            $faculty->id,
            'Faculty member created: ' . $faculty->name,
            $request->all()
        );

        return response()->json(['success' => true, 'message' => 'Faculty member added successfully.', 'faculty' => $faculty]);
    }

    /**
     * Update the specified faculty member.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFaculty(Request $request, $id)
    {
        $faculty = FacultyMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Map the simplified form fields to the database fields
        $facultyData = [
            'name' => $request->name,
            'position' => $request->degree, // Map degree to position field
            'department' => $request->institution, // Map institution to department field
            'specialization' => $request->specialization,
            'education_background' => $request->degree . ' - ' . $request->specialization, // Combine for education background
            'research_description' => $faculty->research_description ?? '', // Keep existing or empty
            
            'university_origin' => $request->institution,
            'sort_order' => $request->sort_order ?? 0
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($faculty->photo_path) {
                $oldPhotoPath = public_path('experts/' . $faculty->photo_path);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('experts'), $fileName);
            $facultyData['photo_path'] = $fileName;
        }

        $faculty->update($facultyData);

        // Log the action
        $this->auditService->log(
            'update',
            'faculty_member',
            $faculty->id,
            'Faculty member updated: ' . $faculty->name,
            $request->all()
        );

        return response()->json(['success' => true, 'message' => 'Faculty member updated successfully.', 'faculty' => $faculty]);
    }

    /**
     * Remove the specified faculty member.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFaculty($id)
    {
        $faculty = FacultyMember::findOrFail($id);

        // Delete photo if exists
        if ($faculty->photo_path) {
            $photoPath = public_path('experts/' . $faculty->photo_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        // Log the action before deletion
        $this->auditService->log(
            'delete',
            'faculty_member',
            $faculty->id,
            'Faculty member deleted: ' . $faculty->name,
            $faculty->toArray()
        );

        $faculty->delete();

        return response()->json(['success' => true, 'message' => 'Faculty member deleted successfully.']);
    }

    /**
     * Toggle faculty member status.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFacultyStatus($id)
    {
        $faculty = FacultyMember::findOrFail($id);
        $faculty->is_active = !$faculty->is_active;
        $faculty->save();

        // Log the action
        $this->auditService->log(
            'update',
            'faculty_member',
            $faculty->id,
            'Faculty member status toggled: ' . $faculty->name . ' (' . ($faculty->is_active ? 'activated' : 'deactivated') . ')',
            ['is_active' => $faculty->is_active]
        );

        return response()->json(['success' => true, 'message' => 'Faculty member status updated.', 'is_active' => $faculty->is_active]);
    }

    /**
     * Store a newly created announcement.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,application,scholarship,event,urgent',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:10',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'priority' => $request->integer('priority', 0),
            'published_at' => $request->published_at ?: now(),
            'expires_at' => $request->expires_at
        ]);

        $this->auditService->log('announcement_created', 'Announcement', $announcement->id,
            'Created announcement: ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully.',
            'announcement' => $announcement
        ]);
    }

    /**
     * Update the specified announcement.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,application,scholarship,event,urgent',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:10',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'priority' => $request->integer('priority', 0),
            'published_at' => $request->published_at ?: $announcement->published_at,
            'expires_at' => $request->expires_at
        ]);

        $this->auditService->log('announcement_updated', 'Announcement', $announcement->id,
            'Updated announcement: ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully.',
            'announcement' => $announcement->fresh()
        ]);
    }

    /**
     * Delete the specified announcement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;

        $announcement->delete();

        $this->auditService->log('announcement_deleted', 'Announcement', $id,
            'Deleted announcement: ' . $title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully.'
        ]);
    }

    /**
     * Toggle the active status of an announcement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleAnnouncementStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        $this->auditService->log('announcement_toggled', 'Announcement', $id,
            'Announcement ' . $status . ': ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement status updated successfully.',
            'announcement' => $announcement
        ]);
    }



    /**
     * Create a new admin user.
     */
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => true,
            'is_default_password' => true,
            'must_change_password' => true,
        ]);

        return redirect()->back()->with('success', 'Admin user created successfully.');
    }
}
