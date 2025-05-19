<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ScholarProfileUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ScholarProfileController extends Controller
{
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
     * @param  \App\Http\Requests\ScholarProfileUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ScholarProfileUpdateRequest $request)
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
            if (!isset($validatedData['university'])) {
                $scholarProfile->university = 'Central Luzon State University';
            }
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

        // Update academic information
        if (isset($validatedData['university'])) {
        $scholarProfile->university = $validatedData['university'];
        }
        if (isset($validatedData['department'])) {
        $scholarProfile->department = $validatedData['department'];
        }
        if (isset($validatedData['program'])) {
        $scholarProfile->program = $validatedData['program'];
        }
        if (isset($validatedData['student_id'])) {
        $scholarProfile->student_id = $validatedData['student_id'];
        }

        // Update contact information
        if (isset($validatedData['phone'])) {
            $scholarProfile->phone = $validatedData['phone'];
        }
        if (isset($validatedData['address'])) {
        $scholarProfile->address = $validatedData['address'];
        }

        // Update optional academic fields
        if (isset($validatedData['major'])) {
            $scholarProfile->major = $validatedData['major'];
        }
        if (isset($validatedData['gpa'])) {
            $scholarProfile->gpa = $validatedData['gpa'];
        }
        if (isset($validatedData['start_date'])) {
            $scholarProfile->start_date = $validatedData['start_date'];
        }
        if (isset($validatedData['expected_completion_date'])) {
            $scholarProfile->expected_completion_date = $validatedData['expected_completion_date'];
        }

        // Update research information
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

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                // Delete old photo if exists
                if ($user->profile_photo) {
                    Storage::delete('public/' . $user->profile_photo);
                }

                // Store new photo
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo = str_replace('public/', '', $path);
                $user->save();

                Log::info('Profile photo updated successfully for user ' . $user->id);
            } catch (\Exception $e) {
                Log::error('Failed to upload profile photo: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'exception' => $e
                ]);
            }
        }

        try {
        $scholarProfile->save();

            // Create audit log for the update if class exists
            if (class_exists('App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => $originalData ? 'updated' : 'created',
                    'entity_type' => 'scholar_profile',
                    'entity_id' => $scholarProfile->id,
                    'old_values' => $originalData ? json_encode($originalData) : null,
                    'new_values' => json_encode($scholarProfile->toArray()),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

        return redirect()->route('scholar.profile')
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
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('scholar.profile')
            ->with('success', 'Password updated successfully.');
    }
}
