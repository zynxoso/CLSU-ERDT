<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SiteSetting;
use App\Services\AuditService;
use Illuminate\Validation\ValidationException;

class SystemSettingsManagement extends Component
{
    // General Settings
    public $site_name = '';
    public $site_description = '';

    // Security Settings
    public $password_expiry_days = 90;
    public $max_login_attempts = 5;

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
