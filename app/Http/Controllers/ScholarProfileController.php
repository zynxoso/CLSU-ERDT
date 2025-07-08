<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ScholarProfileUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;

class ScholarProfileController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
    }

    /**
     * Display the scholar's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        $scholar = $user->scholarProfile;

        if (!$scholar) {
            return redirect()->route('scholar.profile.edit')
                ->with('info', 'Please complete your profile information for the ERDT PRISM ABE Scholarship Program.');
        }

        // Calculate scholarship progress based on real factors
        // This is a simplified calculation, can be made more complex based on actual requirements
        $progressFactors = [
            'profile_complete' => 10, // Profile completion worth 10%
            'documents_complete' => 20, // Documents submission worth 20%
            'coursework' => 40, // Coursework worth 40%
            'research' => 30, // Research progress worth 30%
        ];

        // Start with profile completion
        $scholarProgress = 0;

        // Check if essential profile fields are filled
        $essentialFields = ['university', 'department', 'program', 'student_id', 'phone', 'address'];
        $filledFields = 0;
        foreach ($essentialFields as $field) {
            if (!empty($scholar->$field)) {
                $filledFields++;
            }
        }
        $profileCompletion = ($filledFields / count($essentialFields)) * 100;
        $scholarProgress += ($profileCompletion / 100) * $progressFactors['profile_complete'];

        // For document completion, research progress, and coursework,
        // we would need to fetch data from other tables.
        // For now, using placeholder data:
        $documentsCount = \App\Models\Document::where('scholar_profile_id', $scholar->id)->count();
        $documentsCompletion = min(($documentsCount / 5) * 100, 100); // Assuming 5 required documents
        $scholarProgress += ($documentsCompletion / 100) * $progressFactors['documents_complete'];

        // Coursework progress (placeholder)
        $courseworkProgress = 80; // Example percentage
        $scholarProgress += ($courseworkProgress / 100) * $progressFactors['coursework'];

        // Research progress (placeholder)
        $researchProgress = 50; // Example percentage
        $scholarProgress += ($researchProgress / 100) * $progressFactors['research'];

        // Round to integer
        $scholarProgress = round($scholarProgress);

        // Calculate days remaining (if expected completion date is set)
        $daysRemaining = 0;
        if ($scholar->expected_completion_date) {
            $completionDate = \Carbon\Carbon::parse($scholar->expected_completion_date);
            $today = \Carbon\Carbon::today();
            $daysRemaining = $today->diffInDays($completionDate, false);
            $daysRemaining = max(0, $daysRemaining); // Ensure not negative
        }

        return view('scholar.profile.index', compact(
            'user',
            'scholar',
            'scholarProgress',
            'daysRemaining',
            'courseworkProgress',
            'researchProgress',
            'progressFactors'
        ));
    }

    /**
     * Show the form for editing the scholar profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $scholarProfile = $user->scholarProfile;

        // If there's no profile yet, we're creating a new one
        $isNew = !$scholarProfile;

        return view('scholar.profile.edit', [
            'scholarProfile' => $scholarProfile,
            'isNew' => $isNew
        ]);
    }

    /**
     * Update the specified scholar profile in storage.
     *
     * @param  ScholarProfileUpdateRequest  $request
     * @return RedirectResponse
     */
    public function update(ScholarProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // All validation is handled in the ScholarProfileUpdateRequest
        $validatedData = $request->validated();

        // Store original data for logging
        $originalData = null;

        $scholarProfile = $user->scholarProfile;

        if (!$scholarProfile) {
            // Create a new profile
            $scholarProfile = new ScholarProfile();
            $scholarProfile->user_id = $user->id;
            $scholarProfile->status = 'Pending';

            // Default values if creating new profile
            $scholarProfile->university = 'Central Luzon State University';
            if (!isset($validatedData['department'])) {
                $scholarProfile->department = 'Engineering';
            }
            if (!isset($validatedData['program'])) {
                $scholarProfile->program = 'Master in Agricultural and Biosystems Engineering';
            }
        } else {
            // Store original data for existing profile
            $originalData = $scholarProfile->toArray();
        }

        // Update academic information - always set university to CLSU
        $scholarProfile->university = 'Central Luzon State University';
        if (isset($validatedData['department'])) {
            $scholarProfile->department = $validatedData['department'];
        }
        if (isset($validatedData['program'])) {
            $scholarProfile->program = $validatedData['program'];
        }
        if (isset($validatedData['degree_level'])) {
            $scholarProfile->degree_level = $validatedData['degree_level'];
        }
        if (isset($validatedData['major'])) {
            $scholarProfile->major = $validatedData['major'];
        }

        // Update personal information
        if (isset($validatedData['first_name'])) {
            $scholarProfile->first_name = $validatedData['first_name'];
        }
        if (isset($validatedData['middle_name'])) {
            $scholarProfile->middle_name = $validatedData['middle_name'];
        }
        if (isset($validatedData['last_name'])) {
            $scholarProfile->last_name = $validatedData['last_name'];
        }
        if (isset($validatedData['birth_date'])) {
            $scholarProfile->birth_date = $validatedData['birth_date'];
        }
        if (isset($validatedData['gender'])) {
            $scholarProfile->gender = $validatedData['gender'];
        }

        // Update contact information - sync both phone and contact_number fields
        if (isset($validatedData['phone'])) {
            $scholarProfile->phone = $validatedData['phone'];
            $scholarProfile->contact_number = $validatedData['phone']; // Sync for admin views
        }
        if (isset($validatedData['address'])) {
            $scholarProfile->address = $validatedData['address'];
        }

        // Update optional academic fields
        if (isset($validatedData['start_date'])) {
            $scholarProfile->start_date = $validatedData['start_date'];
        }
        if (isset($validatedData['expected_completion_date'])) {
            $scholarProfile->expected_completion_date = $validatedData['expected_completion_date'];
        }

        // Update bachelor's degree information
        if (isset($validatedData['bachelor_degree'])) {
            $scholarProfile->bachelor_degree = $validatedData['bachelor_degree'];
        }
        if (isset($validatedData['bachelor_university'])) {
            $scholarProfile->bachelor_university = $validatedData['bachelor_university'];
        }

        // Update research information (commented out fields in form)
        if (isset($validatedData['research_title'])) {
            $scholarProfile->research_title = $validatedData['research_title'];
        }
        if (isset($validatedData['research_area'])) {
            $scholarProfile->research_area = $validatedData['research_area'];
        }
        if (isset($validatedData['research_abstract'])) {
            $scholarProfile->research_abstract = $validatedData['research_abstract'];
        }
        if (isset($validatedData['advisor'])) {
            $scholarProfile->advisor = $validatedData['advisor'];
        }

        // Handle profile photo removal
        if ($request->has('remove_photo') && $request->remove_photo == '1') {
            try {
                // Delete existing photo if it exists
                if ($scholarProfile->profile_photo) {
                    $photoPath = public_path('images/' . $scholarProfile->profile_photo);
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
                $scholarProfile->profile_photo = null;
                Log::info('Profile photo removed for scholar profile ' . $scholarProfile->id);
            } catch (\Exception $e) {
                Log::error('Failed to remove profile photo: ' . $e->getMessage(), [
                    'scholar_profile_id' => $scholarProfile->id,
                    'exception' => $e
                ]);
            }
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                // Delete old photo if exists
                if ($scholarProfile->profile_photo) {
                    $oldPhotoPath = public_path('images/' . $scholarProfile->profile_photo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                // Ensure images directory exists
                $imagesDir = public_path('images');
                if (!is_dir($imagesDir)) {
                    mkdir($imagesDir, 0755, true);
                }

                // Store new photo in public/images
                $file = $request->file('profile_photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($imagesDir, $fileName);
                $scholarProfile->profile_photo = $fileName;

                Log::info('Profile photo updated successfully for scholar profile ' . $scholarProfile->id);
            } catch (\Exception $e) {
                Log::error('Failed to upload profile photo: ' . $e->getMessage(), [
                    'scholar_profile_id' => $scholarProfile->id,
                    'exception' => $e
                ]);
            }
        }

        try {
            $scholarProfile->save();

            // Update user's name if name fields were changed
            if (isset($validatedData['first_name']) || isset($validatedData['middle_name']) || isset($validatedData['last_name'])) {
                $newName = $scholarProfile->first_name . ' ' . ($scholarProfile->middle_name ? $scholarProfile->middle_name . ' ' : '') . $scholarProfile->last_name;
                if ($user->name !== $newName) {
                    $user->name = $newName;
                    $user->save();
                }
            }

            // Create audit log using the AuditService
            if ($originalData) {
                $this->auditService->logUpdate('ScholarProfile', $scholarProfile->id, $originalData, $scholarProfile->toArray());
            } else {
                $this->auditService->logCreate('ScholarProfile', $scholarProfile->id, $scholarProfile->toArray());
            }

            return redirect()->route('scholar.profile.edit')
                ->with('success', 'Your profile has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update scholar profile: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update scholar profile. Please try again.');
        }
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showSettings()
    {
        return view('scholar.settings');
    }

    /**
     * Display the password change form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChangePasswordForm()
    {
        return view('scholar.change-password');
    }

    /**
     * Update the scholar's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Get dynamic password expiry days from settings
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        $user->password = Hash::make($request->new_password);

        // Clear default password flags
        $user->is_default_password = false;
        $user->must_change_password = false;

        // Set password expiration using dynamic settings
        $user->setPasswordExpiration();

        // Save the user
        $user->save();

        // Clear session warning flag
        session()->forget('password_expiry_warning_shown');
        session()->forget('show_password_modal');

        return redirect()->route('scholar.dashboard')
            ->with('success', "Password updated successfully. Your password will expire in {$passwordExpiryDays} days.");
    }
}
