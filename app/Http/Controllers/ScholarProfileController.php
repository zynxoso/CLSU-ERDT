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

        // Check if essential profile fields are filled (only fields scholars can control)
        $essentialFields = ['first_name', 'last_name', 'birth_date', 'gender', 'contact_number'];
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

        // Calculate days remaining (placeholder - could be based on scholarship duration)
        $daysRemaining = 0;

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
        /** @var \Illuminate\Http\Request $request */
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // All validation is handled in the ScholarProfileUpdateRequest
        $validatedData = $request->validated();

        // Store original data for logging
        $originalData = null;

        /** @var \App\Models\ScholarProfile|null $scholarProfile */
        $scholarProfile = $user->scholarProfile;

        if (!$scholarProfile) {
            // Create a new profile
            $scholarProfile = new ScholarProfile();
            $scholarProfile->user_id = $user->id;
            $scholarProfile->setAttribute('status', 'Pending');
        } else {
            // Store original data for existing profile
            $originalData = $scholarProfile->toArray();
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
        if (isset($validatedData['suffix'])) {
            $scholarProfile->suffix = $validatedData['suffix'];
        }
        if (isset($validatedData['birth_date'])) {
            $scholarProfile->birth_date = $validatedData['birth_date'];
        }
        if (isset($validatedData['gender'])) {
            $scholarProfile->gender = $validatedData['gender'];
        }

        // Update contact information
        if (isset($validatedData['phone'])) {
            Log::info('Updating contact_number', [
                'user_id' => $user->id,
                'phone_value' => $validatedData['phone'],
                'current_contact_number' => $scholarProfile->contact_number ?? 'null'
            ]);
            try {
                $scholarProfile->contact_number = $validatedData['phone'];
                Log::info('Contact number updated successfully', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to update contact_number', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'phone_value' => $validatedData['phone']
                ]);
                throw $e;
            }
        }

        // Update address information
        if (isset($validatedData['street'])) {
            $scholarProfile->street = $validatedData['street'];
        }
        if (isset($validatedData['village'])) {
            $scholarProfile->village = $validatedData['village'];
        }
        if (isset($validatedData['town'])) {
            $scholarProfile->town = $validatedData['town'];
        }
        if (isset($validatedData['province'])) {
            $scholarProfile->province = $validatedData['province'];
        }
        if (isset($validatedData['zipcode'])) {
            $scholarProfile->zipcode = $validatedData['zipcode'];
        }
        if (isset($validatedData['district'])) {
            $scholarProfile->district = $validatedData['district'];
        }
        if (isset($validatedData['region'])) {
            $scholarProfile->region = $validatedData['region'];
        }
        if (isset($validatedData['country'])) {
            $scholarProfile->country = $validatedData['country'];
        }



        try {
            // Debug: Log the data being saved
            Log::info('Attempting to save scholar profile', [
                'user_id' => $user->id,
                'validated_data' => $validatedData,
                'scholar_profile_data' => $scholarProfile->toArray()
            ]);

            $scholarProfile->save();

            Log::info('Scholar profile saved successfully', ['profile_id' => $scholarProfile->id]);

            // Update user's name if name fields were changed
            if (isset($validatedData['first_name']) || isset($validatedData['middle_name']) || isset($validatedData['last_name'])) {
                $newName = $scholarProfile->first_name . ' ' . ($scholarProfile->middle_name ? $scholarProfile->middle_name . ' ' : '') . $scholarProfile->last_name;
                if ($user->name !== $newName) {
                    Log::info('Updating user name', ['old_name' => $user->name, 'new_name' => $newName]);
                    $user->name = $newName;
                    $user->save();
                }
            }

            // Create audit log using the AuditService
            try {
                if ($originalData) {
                    $this->auditService->logUpdate('ScholarProfile', $scholarProfile->id, $originalData, $scholarProfile->toArray());
                } else {
                    $this->auditService->logCreate('ScholarProfile', $scholarProfile->id, $scholarProfile->toArray());
                }
                Log::info('Audit log created successfully');
            } catch (\Exception $auditException) {
                Log::warning('Failed to create audit log: ' . $auditException->getMessage());
                // Continue execution even if audit logging fails
            }

            return redirect()->route('scholar.profile.edit')
                ->with('success', 'Your profile has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update scholar profile', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'validated_data' => $validatedData
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update scholar profile. Error: ' . $e->getMessage());
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
