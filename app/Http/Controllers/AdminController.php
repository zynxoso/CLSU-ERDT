<?php

namespace App\Http\Controllers;

// Mga kailangan na library at model para sa controller
use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\CustomNotification;

/**
 * AdminController - Nag-hahandle ng lahat ng admin functions
 * Ito ang nagko-control sa dashboard, settings, at iba pang admin features
 */
class AdminController extends Controller
{
    // Service na ginagamit para sa audit logging
    protected $auditService;

    /**
     * Constructor - Ginagawa kapag ginawa ang bagong instance ng controller
     * Dito natin sinisiguro na admin lang ang makaka-access
     *
     * @param AuditService $auditService - Service para sa pag-log ng mga actions
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        // Siguraduhing naka-login ang user bago mag-access
        $this->middleware('auth');

        // Custom middleware para sa role checking
        // Ginagamit natin anonymous function para iwas sa conflict
        $this->middleware(function ($request, $next) {
            // Tingnan kung admin ba ang naka-login na user
            if (Auth::user()->role !== 'admin') {
                // Kung hindi admin, bawal mag-access (403 error)
                abort(403, 'Unauthorized action. You do not have the required role.');
            }
            return $next($request);
        });

        // I-assign ang audit service sa property
        $this->auditService = $auditService;
    }

    /**
     * Ipapakita ang admin dashboard
     * Dito makikita ang lahat ng statistics at recent activities
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Kunin ang naka-login na admin user
        $user = Auth::user();

        // Kumuha ng lahat ng data tungkol sa mga scholar
        $scholars = ScholarProfile::all();
        $totalScholars = $scholars->count(); // Kabuuang bilang ng scholars
        $pendingScholars = ScholarProfile::whereStatus('Pending')->count(); // Mga naghihintay pa
        $activeScholars = ScholarProfile::whereStatus('Active')->count(); // Mga aktibong scholars
        $recentScholars = $scholars->sortByDesc('created_at')->take(5); // 5 pinakabagong scholars

        // Kumuha ng data tungkol sa mga dokumento
        $documents = Document::all();
        $pendingDocuments = $documents->where('status', 'Uploaded')->count(); // Mga dokumento na hindi pa na-verify
        $recentDocuments = $documents->sortByDesc('created_at')->take(5); // 5 pinakabagong dokumento

        // Kumuha ng data tungkol sa mga fund request
        $fundRequests = FundRequest::all();
        // Mga fund request na naghihintay pa ng approval
        $pendingFundRequests = $fundRequests->whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->count();
        $approvedRequests = $fundRequests->where('status', 'Approved')->count(); // Mga na-approve na
        $recentRequests = $fundRequests->sortByDesc('created_at')->take(3); // 3 pinakabagong requests
        $recentFundRequests = $recentRequests; // Para sa view (alias lang)
        $pendingRequests = $pendingFundRequests + $pendingDocuments; // Kabuuang pending items
        $totalDisbursed = $fundRequests->where('status', 'Approved')->sum('amount'); // Kabuuang na-release na pera

        // Kalkulahin ang na-release na pera ngayong buwan
        $currentMonth = now()->month; // Kasalukuyang buwan
        $currentYear = now()->year; // Kasalukuyang taon
        $disbursedThisMonth = $fundRequests->where('status', 'Approved')
            ->filter(function ($request) use ($currentMonth, $currentYear) {
                // I-parse ang date kung kailan na-approve
                $approvedDate = \Carbon\Carbon::parse($request->updated_at);
                // Tingnan kung same month at year
                return $approvedDate->month == $currentMonth && $approvedDate->year == $currentYear;
            })->sum('amount'); // I-sum ang mga amount

        // Kumuha ng data tungkol sa mga manuscript
        $manuscripts = Manuscript::all();
        $recentManuscripts = $manuscripts->sortByDesc('created_at')->take(5); // 5 pinakabagong manuscripts

        // Kalkulahin ang completion metrics
        // Percentage ng mga natapos na scholars
        $completionRate = $scholars->count() > 0 ? round((ScholarProfile::whereStatus('Graduated')->count() / $scholars->count()) * 100) : 0;
        // Bilang ng natapos ngayong taon
        $completionsThisYear = ScholarProfile::whereStatus('Completed')
            ->whereYear('updated_at', $currentYear)
            ->count();

        // Program distribution (wala na kasi tinanggal ang program field)
        $programCounts = collect();

        // Kumuha ng mga notification para sa admin
        $notifications = CustomNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Pinakabago muna
            ->get();
        $recentScholarActivity = collect([]); // Placeholder lang muna

        // Kumuha ng mga recent audit logs (mga nangyaring actions)
        $recentLogs = AuditLog::with('user') // Kasama ang user info
            ->orderBy('created_at', 'desc') // Pinakabago muna
            ->limit(3) // 3 lang
            ->get();

        // Kumuha ng mga recent notifications para sa admin
        $recentNotifications = CustomNotification::where('user_id', $user->id)
            ->whereIn('type', [
                'App\\Notifications\\NewFundRequestSubmitted', // Bagong fund request
                'App\\Notifications\\NewManuscriptSubmitted'   // Bagong manuscript
            ])
            ->orderBy('created_at', 'desc') // Pinakabago muna
            ->limit(5) // 5 lang
            ->get();

        // I-return ang dashboard view kasama ang lahat ng data
        return view('admin.dashboard', compact(
            'user',
            'scholars',
            'totalScholars',
            'pendingScholars',
            'activeScholars',
            'recentScholars',
            'fundRequests',
            'pendingFundRequests',
            'pendingRequests',
            'approvedRequests',
            'recentRequests',
            'recentFundRequests',
            'totalDisbursed',
            'disbursedThisMonth',
            'documents',
            'pendingDocuments',
            'recentDocuments',
            'manuscripts',
            'recentManuscripts',
            'completionRate',
            'completionsThisYear',
            'notifications',
            'recentScholarActivity',
            'recentLogs',
            'programCounts',
            'recentNotifications'
        ));
    }

    /**
     * Ipapakita ang form para sa pag-change ng password
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChangePasswordForm()
    {
        // I-return ang change password view
        return view('admin.change-password');
    }

    /**
     * I-update ang password ng admin
     * Ginagamit kapag nag-submit ng change password form
     *
     * @param  \App\Http\Requests\ChangePasswordRequest  $request - Validated request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        // Kunin ang naka-login na user
        $user = Auth::user();
        // Kunin ang validated data mula sa request
        $validated = $request->validated();

        // Kunin ang password expiry days mula sa settings (default: 90 days)
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        // I-hash ang bagong password at i-save
        $user->password = Hash::make($validated['new_password']);

        // I-clear ang mga default password flags
        $user->is_default_password = false;    // Hindi na default password
        $user->must_change_password = false;   // Hindi na kailangan mag-change

        // I-set ang password expiration gamit ang dynamic settings
        $user->setPasswordExpiration();

        // I-save ang user sa database
        $user->save();

        // I-clear ang mga session warning flags
        session()->forget('password_expiry_warning_shown');
        session()->forget('show_password_modal');

        // I-log ang password change sa audit trail
        $this->auditService->log('password_changed', 'User', $user->id, 'Password changed successfully');

        // I-redirect sa settings page para sa better UX
        $redirectRoute = 'admin.settings';

        return redirect()->route($redirectRoute)
            ->with('success', "Password updated successfully. Your password will expire in {$passwordExpiryDays} days.");
    }

    /**
     * Ipapakita ang profile edit form ng admin
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editProfile()
    {
        // I-return ang admin profile view
        return view('admin.profile');
    }

    /**
     * I-update ang profile information ng admin
     * Ginagamit kapag nag-submit ng profile edit form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Kunin ang naka-login na user
        $user = Auth::user();

        // I-validate ang mga input fields
        $request->validate([
            'name' => ['required', 'string', 'max:255'],                                    // Name - required
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id], // Email - unique except sa current user
            'phone' => ['nullable', 'string', 'max:20'],                                    // Phone - optional
            'position' => ['nullable', 'string', 'max:255'],                                // Position - optional
        ]);

        // I-update ang user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position' => $request->position,
        ]);

        // I-log ang profile update sa audit trail
        $this->auditService->log('profile_updated', 'User', $user->id,
            'Updated profile - Name: ' . $request->name . ', Email: ' . $request->email
        );

        // I-redirect pabalik sa profile edit page na may success message
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * I-update ang password ng admin mula sa profile page
     * Iba ito sa changePassword kasi galing sa profile page
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // I-validate ang mga password fields
        $request->validate([
            // I-check kung tama ang current password
            'current_password' => ['required', function ($attribute, $value, $fail) {
                // I-compare ang input sa naka-save na password
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            // Bagong password - minimum 8 characters at may confirmation
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Kunin ang naka-login na user
        $user = Auth::user();

        // Kunin ang password expiry days mula sa settings (default: 90 days)
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        // I-hash ang bagong password at i-save
        $user->password = Hash::make($request->new_password);

        // I-clear ang mga default password flags
        $user->is_default_password = false;    // Hindi na default password
        $user->must_change_password = false;   // Hindi na kailangan mag-change

        // I-set ang password expiration gamit ang dynamic settings
        $user->setPasswordExpiration();

        // I-save ang user sa database
        $user->save();

        // I-log ang password change sa audit trail (specify na galing sa profile page)
        $this->auditService->log('password_changed', 'User', $user->id, 'Password changed from profile page');

        // I-redirect pabalik sa profile edit page na may success message
        return redirect()->route('admin.profile.edit')
            ->with('success', "Password updated successfully. Your password will expire in {$passwordExpiryDays} days.");
    }

    /**
     * I-update ang notification preferences ng admin
     * Dito pwedeng i-on/off ang mga notifications
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        // Kunin ang naka-login na user
        $user = Auth::user();

        // I-update ang notification preferences
        // Ginagamit ang has() para i-check kung naka-check ang checkbox
        $user->update([
            'email_notifications' => $request->has('email_notifications'),           // Email notifications on/off
            'fund_request_notifications' => $request->has('fund_request_notifications'), // Fund request notifications
            'document_notifications' => $request->has('document_notifications'),     // Document notifications
            'manuscript_notifications' => $request->has('manuscript_notifications'), // Manuscript notifications
        ]);

        // I-log ang notification preferences update sa audit trail
        $this->auditService->log('notification_preferences_updated', 'User', $user->id, 'Updated notification preferences');

        // I-redirect pabalik sa profile edit page na may success message
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Ipapakita ang admin settings page
     * Dito pwedeng i-configure ang system settings
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        // Kumuha ng mock settings data - sa tunay na application, galing ito sa settings table
        $settings = (object) [
            'site_name' => 'CLSU-ERDT',
            'site_description' => 'Central Luzon State University - Engineering Research and Development for Technology Scholarship Management System',
            'contact_email' => 'erdt@clsu.edu.ph',
            'contact_phone' => '+63 44 456 0680',
            'default_stipend' => 20000,              // Default na stipend amount
            'default_book_allowance' => 10000,       // Default na book allowance
            'default_research_allowance' => 50000,   // Default na research allowance
            'max_scholarship_duration' => 36,        // Maximum na duration ng scholarship (months)
            'required_documents' => ['Transcript', 'ID', 'Enrollment', 'Grades'], // Required documents
        ];

        // Kumuha ng mga users para sa user management section
        // Hindi kasama ang mga scholar, admin at super admin lang
        $users = \App\Models\User::where('role', '!=', 'scholar')->paginate(10);

        // I-return ang settings view kasama ang settings at users data
        return view('admin.settings.index', compact('settings', 'users'));
    }

    /**
     * I-update ang general settings ng system
     * Ginagamit para sa basic site information
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        // I-validate ang mga input fields
        $request->validate([
            'site_name' => 'required|string|max:255',        // Site name - required
            'site_description' => 'nullable|string',         // Site description - optional
            'contact_email' => 'required|email|max:255',     // Contact email - required at valid email
            'contact_phone' => 'nullable|string|max:20',     // Contact phone - optional
        ]);

        // Sa tunay na application, i-save ito sa settings table
        // Sa ngayon, i-log lang natin at magpapakita ng success message

        // I-log ang settings update sa audit trail
        $this->auditService->log('settings_updated', 'Settings', null,
            'Updated general settings - Site Name: ' . $request->site_name
        );

        // I-redirect pabalik sa settings page na may success message
        return redirect()->route('admin.settings')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * I-update ang scholarship settings
     * Ginagamit para sa mga default amounts at requirements
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateScholarshipSettings(Request $request)
    {
        // I-validate ang mga scholarship settings
        $request->validate([
            'default_stipend' => 'required|numeric|min:0',           // Default stipend - required at positive number
            'default_book_allowance' => 'required|numeric|min:0',    // Default book allowance - required at positive
            'default_research_allowance' => 'required|numeric|min:0', // Default research allowance - required at positive
            'max_scholarship_duration' => 'required|integer|min:1',  // Max duration - required at minimum 1 month
            'required_documents' => 'nullable|array',                // Required documents - optional array
        ]);

        // Sa tunay na application, i-save ito sa settings table
        // Sa ngayon, i-log lang natin at magpapakita ng success message

        // I-log ang scholarship settings update sa audit trail
        $this->auditService->log('scholarship_settings_updated', 'Settings', null,
            'Updated scholarship settings - Default Stipend: ₱' . number_format($request->default_stipend)
        );

        // I-redirect pabalik sa settings page na may success message
        return redirect()->route('admin.settings')
            ->with('success', 'Scholarship settings updated successfully.');
    }




}
