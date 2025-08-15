<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SiteSetting;
use App\Services\AuditService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SystemSettingsManagement extends Component
{
    // General Settings
    public $site_name = '';
    public $site_description = '';

    // Security Settings
    public $password_expiry_days = 90;
    public $max_login_attempts = 5;

    // Change Password
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    // UI State
    public $activeTab = 'general';
    public $isSaving = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Load General Settings
        $generalSettings = SiteSetting::getByGroup('general');
        $this->site_name = $generalSettings['site_name'] ?? 'CLSU-ERDT Scholar Management';
        $this->site_description = $generalSettings['site_description'] ?? 'CLSU-ERDT Scholar Management System for Agricultural and Biosystems Engineering';

        // Load Security Settings
        $securitySettings = SiteSetting::getByGroup('security');
        $this->password_expiry_days = $securitySettings['password_expiry_days'] ?? 90;
        $this->max_login_attempts = $securitySettings['max_login_attempts'] ?? 5;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->clearMessages();
    }

    public function saveGeneralSettings()
    {
        $this->isSaving = true;
        $this->clearMessages();

        try {
            $this->validate([
                'site_name' => 'required|string|max:255',
                'site_description' => 'required|string|max:1000',
            ]);

            $settings = [
                'site_name' => ['value' => $this->site_name, 'type' => 'string'],
                'site_description' => ['value' => $this->site_description, 'type' => 'string'],
            ];

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'general');
            }

            $this->auditService->log('general_settings_updated', 'System Settings', null,
                'Updated general settings via Livewire');

            $this->successMessage = 'General settings updated successfully!';

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while saving the settings.';
        } finally {
            $this->isSaving = false;
        }
    }

    public function saveSecuritySettings()
    {
        $this->isSaving = true;
        $this->clearMessages();

        try {
            $this->validate([
                'password_expiry_days' => 'required|integer|min:1|max:365',
                'max_login_attempts' => 'required|integer|min:1|max:20',
            ]);

            $settings = [
                'password_expiry_days' => ['value' => $this->password_expiry_days, 'type' => 'integer'],
                'max_login_attempts' => ['value' => $this->max_login_attempts, 'type' => 'integer'],
            ];

            foreach ($settings as $key => $config) {
                SiteSetting::set($key, $config['value'], $config['type'], 'security');
            }

            $this->auditService->log('security_settings_updated', 'System Settings', null,
                'Updated security settings via Livewire');

            $this->successMessage = 'Security settings updated successfully!';

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while saving the security settings.';
        } finally {
            $this->isSaving = false;
        }
    }

    public function changePassword()
    {
        $this->isSaving = true;
        $this->clearMessages();

        try {
            $this->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required',
            ]);

            $user = Auth::user();

            // Verify current password
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'The current password is incorrect.');
                return;
            }

            // Update password
            $user->update([
                'password' => Hash::make($this->new_password),
                'password_changed_at' => now(),
                'password_expires_at' => now()->addDays($this->password_expiry_days),
            ]);

            // Clear password fields
            $this->current_password = '';
            $this->new_password = '';
            $this->new_password_confirmation = '';

            $this->auditService->log('password_changed', 'User', $user->id,
                'Super admin password changed via system settings');

            $this->successMessage = 'Password changed successfully!';

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while changing the password.';
        } finally {
            $this->isSaving = false;
        }
    }

    public function clearCache()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            $this->auditService->log('cache_cleared', 'System', null,
                'System cache cleared via system settings');

            $this->successMessage = 'Cache cleared successfully!';
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while clearing the cache.';
        }
    }

    public function clearMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.admin.system-settings-management');
    }
}
