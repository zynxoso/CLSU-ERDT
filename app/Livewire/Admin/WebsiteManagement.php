<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement;
use App\Models\FacultyMember;
use App\Services\AuditService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Exception;

class WebsiteManagement extends Component
{
    use WithFileUploads;

    // Tab management
    public $activeTab = 'content';

    // Content Management Properties - Updated to match actual landing page content
    public $content = [
        // Hero Section (Homepage)
        'hero_title' => 'Welcome to the',
        'hero_subtitle' => 'CLSU-ERDT Portal',
        'hero_tagline' => 'Streamlining Your Scholarship Journey',
        'hero_description' => 'This official portal for the Engineering Research & Development for Technology (ERDT) program at CLSU is designed to help you manage your scholarship, track your progress, and access resources seamlessly.',

        // About Section (About Page)
        'about_hero_title' => 'Engineering Excellence',
        'about_hero_subtitle' => 'at CLSU-ERDT',
        'about_hero_tagline' => 'Fostering Advanced Education & Research',
        'about_hero_description' => 'Discover our mission to develop world-class engineers and researchers who will drive innovation and technological advancement in the Philippines and beyond.',

        // Mission & Vision
        'mission' => 'To develop world-class engineers and researchers through advanced graduate education and cutting-edge research programs that address national development priorities and contribute to global technological advancement.',
        'vision' => 'To be the premier center of excellence in engineering research and development, producing innovative leaders who drive sustainable technological solutions for national competitiveness and global impact.',

        // Statistics Section
        'stat_universities' => '8',
        'stat_universities_label' => 'Partner Universities',
        'stat_stipend' => '₱38K',
        'stat_stipend_label' => 'Monthly Stipend',
        'stat_scholars' => '200+',
        'stat_scholars_label' => 'Scholars Supported',
        'stat_research_areas' => '15+',
        'stat_research_areas_label' => 'Research Areas',

        // About Page Statistics
        'about_stat_years' => '25+',
        'about_stat_years_label' => 'Years of Excellence',
        'about_stat_graduates' => '500+',
        'about_stat_graduates_label' => 'Graduates',

        // Contact Information
        'contact_email' => 'erdt@clsu.edu.ph',
        'contact_phone' => '0920-9312126',
        'contact_address' => "CLSU-ERDT Office, Engineering Building\nCentral Luzon State University\nScience City of Muñoz, Nueva Ecija",

        // ERDT Description (About Page)
        'erdt_description' => 'The ERDT program is a flagship initiative of the Department of Science and Technology (DOST) designed to accelerate the country\'s technological development through advanced engineering education and research. Established to address the critical need for highly skilled engineers and researchers, ERDT has become the premier scholarship program for graduate studies in engineering.',

        // Program Benefits Section
        'benefits_title' => 'A System Built for Your Success',
        'benefits_description' => 'The CLSU-ERDT portal provides a comprehensive suite of tools to support you throughout your scholarship.',

        // Call to Action
        'cta_title' => 'Ready to Join Our Community?',
        'cta_description' => 'Become part of a prestigious community of engineers and researchers who are making a difference. Start your journey with CLSU-ERDT today and contribute to the advancement of engineering and technology in the Philippines.'
    ];

    public $heroImage;

    // Announcements Properties
    public $announcements = [];
    public $showAnnouncementModal = false;
    public $editingAnnouncement = null;
    public $announcementForm = [
        'title' => '',
        'content' => '',
        'type' => 'general',
        'is_active' => true,
        'priority' => 0
    ];

    // Faculty Properties
    public $facultyMembers = [];
    public $showFacultyModal = false;
    public $editingFaculty = null;
    public $facultyForm = [
        'name' => '',
        'position' => '',
        'specialization' => '',
        'department' => '',
        'sort_order' => 0
    ];
    public $facultyPhoto;

    // UI State
    public $isProcessing = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        $this->loadData();
        $this->loadWebsiteContent();
    }

    public function loadData()
    {
        // Load announcements
        $this->announcements = Announcement::orderByPriority()->get()->toArray();

        // Load faculty members
        $this->facultyMembers = FacultyMember::ordered()->get()->toArray();
    }

    public function loadWebsiteContent()
    {
        // Load content from database or use defaults
        $websiteSettings = \App\Models\SiteSetting::getByGroup('website');

        foreach ($this->content as $key => $defaultValue) {
            $this->content[$key] = $websiteSettings[$key] ?? $defaultValue;
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->clearMessages();
    }

    // Content Management Methods
    public function saveContent()
    {
        $this->clearMessages();
        $this->isProcessing = true;

        try {
            // Save content to database
            foreach ($this->content as $key => $value) {
                \App\Models\SiteSetting::set(
                    $key,
                    $value,
                    'string',
                    'website',
                    $this->getContentDescription($key)
                );
            }

            // Handle hero image upload if provided
            if ($this->heroImage) {
                $heroImagePath = $this->heroImage->store('website/hero', 'public');
                \App\Models\SiteSetting::set(
                    'hero_image_path',
                    $heroImagePath,
                    'string',
                    'website',
                    'Hero section background image path'
                );
            }

            // Log the content update
            $this->auditService->log(
                'website_content_updated',
                'Website Content',
                null,
                'Updated website content settings'
            );

            $this->successMessage = 'Website content updated successfully!';
            $this->heroImage = null;

        } catch (Exception $e) {
            $this->errorMessage = 'Failed to update content: ' . $e->getMessage();
        } finally {
            $this->isProcessing = false;
        }
    }

    private function getContentDescription($key)
    {
        $descriptions = [
            'hero_title' => 'Homepage hero main title',
            'hero_subtitle' => 'Homepage hero subtitle',
            'hero_tagline' => 'Homepage hero tagline',
            'hero_description' => 'Homepage hero description text',
            'about_hero_title' => 'About page hero main title',
            'about_hero_subtitle' => 'About page hero subtitle',
            'about_hero_tagline' => 'About page hero tagline',
            'about_hero_description' => 'About page hero description text',
            'mission' => 'Organization mission statement',
            'vision' => 'Organization vision statement',
            'stat_universities' => 'Partner universities count',
            'stat_universities_label' => 'Partner universities label',
            'stat_stipend' => 'Monthly stipend amount',
            'stat_stipend_label' => 'Monthly stipend label',
            'stat_scholars' => 'Scholars supported count',
            'stat_scholars_label' => 'Scholars supported label',
            'stat_research_areas' => 'Research areas count',
            'stat_research_areas_label' => 'Research areas label',
            'about_stat_years' => 'Years of excellence count',
            'about_stat_years_label' => 'Years of excellence label',
            'about_stat_graduates' => 'Graduates count',
            'about_stat_graduates_label' => 'Graduates label',
            'contact_email' => 'Primary contact email address',
            'contact_phone' => 'Primary contact phone number',
            'contact_address' => 'Primary contact address',
            'erdt_description' => 'ERDT program description',
            'benefits_title' => 'Program benefits section title',
            'benefits_description' => 'Program benefits section description',
            'cta_title' => 'Call to action section title',
            'cta_description' => 'Call to action section description'
        ];

        return $descriptions[$key] ?? 'Website content setting';
    }

    // Announcement Management Methods
    public function openAnnouncementModal($announcementId = null)
    {
        if ($announcementId) {
            $this->editingAnnouncement = collect($this->announcements)->firstWhere('id', $announcementId);
            $this->announcementForm = [
                'title' => $this->editingAnnouncement['title'] ?? '',
                'content' => $this->editingAnnouncement['content'] ?? '',
                'type' => $this->editingAnnouncement['type'] ?? 'general',
                'is_active' => $this->editingAnnouncement['is_active'] ?? true,
                'priority' => $this->editingAnnouncement['priority'] ?? 0
            ];
        } else {
            $this->editingAnnouncement = null;
            $this->resetAnnouncementForm();
        }
        $this->showAnnouncementModal = true;
        $this->clearMessages();
    }

    public function closeAnnouncementModal()
    {
        $this->showAnnouncementModal = false;
        $this->editingAnnouncement = null;
        $this->resetAnnouncementForm();
    }

    public function resetAnnouncementForm()
    {
        $this->announcementForm = [
            'title' => '',
            'content' => '',
            'type' => 'general',
            'is_active' => true,
            'priority' => 0
        ];
    }

    public function saveAnnouncement()
    {
        $this->clearMessages();

        $this->validate([
            'announcementForm.title' => 'required|string|max:255',
            'announcementForm.content' => 'required|string',
            'announcementForm.type' => 'required|in:general,application,scholarship,event,urgent',
            'announcementForm.priority' => 'required|integer|min:0|max:10',
        ]);

        try {
            if ($this->editingAnnouncement) {
                // Update existing announcement
                $announcement = Announcement::findOrFail($this->editingAnnouncement['id']);
                $announcement->update($this->announcementForm);

                $this->auditService->log(
                    'announcement_updated',
                    'Announcement',
                    $announcement->id,
                    'Updated announcement: ' . $announcement->title
                );

                $message = 'Announcement updated successfully!';
            } else {
                // Create new announcement
                $announcement = Announcement::create($this->announcementForm);

                $this->auditService->log(
                    'announcement_created',
                    'Announcement',
                    $announcement->id,
                    'Created announcement: ' . $announcement->title
                );

                $message = 'Announcement created successfully!';
            }

            $this->loadData();
            $this->closeAnnouncementModal();
            $this->successMessage = $message;

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->errorMessage = 'Failed to save announcement: ' . $e->getMessage();
        }
    }

    public function toggleAnnouncementStatus($announcementId)
    {
        $this->clearMessages();

        try {
            $announcement = Announcement::findOrFail($announcementId);
            $announcement->update(['is_active' => !$announcement->is_active]);

            $status = $announcement->is_active ? 'activated' : 'deactivated';

            $this->auditService->log(
                'announcement_status_changed',
                'Announcement',
                $announcement->id,
                "Announcement {$status}: " . $announcement->title
            );

            $this->loadData();
            $this->successMessage = "Announcement {$status} successfully!";

        } catch (Exception $e) {
            $this->errorMessage = 'Failed to update announcement status: ' . $e->getMessage();
        }
    }

    public function deleteAnnouncement($announcementId)
    {
        $this->clearMessages();

        try {
            $announcement = Announcement::findOrFail($announcementId);
            $title = $announcement->title;

            $announcement->delete();

            $this->auditService->log(
                'announcement_deleted',
                'Announcement',
                $announcementId,
                'Deleted announcement: ' . $title
            );

            $this->loadData();
            $this->successMessage = 'Announcement deleted successfully!';

        } catch (Exception $e) {
            $this->errorMessage = 'Failed to delete announcement: ' . $e->getMessage();
        }
    }

    // Faculty Management Methods
    public function openFacultyModal($facultyId = null)
    {
        if ($facultyId) {
            $this->editingFaculty = collect($this->facultyMembers)->firstWhere('id', $facultyId);
            $this->facultyForm = [
                'name' => $this->editingFaculty['name'] ?? '',
                'position' => $this->editingFaculty['position'] ?? '',
                'specialization' => $this->editingFaculty['specialization'] ?? '',
                'department' => $this->editingFaculty['department'] ?? '',
                'sort_order' => $this->editingFaculty['sort_order'] ?? 0
            ];
        } else {
            $this->editingFaculty = null;
            $this->resetFacultyForm();
        }
        $this->showFacultyModal = true;
        $this->clearMessages();
    }

    public function closeFacultyModal()
    {
        $this->showFacultyModal = false;
        $this->editingFaculty = null;
        $this->resetFacultyForm();
        $this->facultyPhoto = null;
    }

    public function resetFacultyForm()
    {
        $this->facultyForm = [
            'name' => '',
            'position' => '',
            'specialization' => '',
            'department' => '',
            'sort_order' => 0
        ];
    }

    public function saveFaculty()
    {
        $this->clearMessages();

        $this->validate([
            'facultyForm.name' => 'required|string|max:255',
            'facultyForm.position' => 'required|string|max:255',
            'facultyForm.specialization' => 'required|string|max:255',
            'facultyForm.department' => 'required|string|max:255',
            'facultyForm.sort_order' => 'required|integer|min:0',
            'facultyPhoto' => 'nullable|image|max:2048'
        ]);

        try {
            $facultyData = $this->facultyForm;

            // Handle photo upload
            if ($this->facultyPhoto) {
                $fileName = time() . '_' . $this->facultyPhoto->getClientOriginalName();
                $this->facultyPhoto->move(public_path('experts'), $fileName);
                $facultyData['photo_path'] = $fileName;
            }

            if ($this->editingFaculty) {
                // Update existing faculty
                $faculty = FacultyMember::findOrFail($this->editingFaculty['id']);

                // Delete old photo if new one is uploaded
                if ($this->facultyPhoto && $faculty->photo_path) {
                    $oldPhotoPath = public_path('experts/' . $faculty->photo_path);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $faculty->update($facultyData);

                $this->auditService->log(
                    'faculty_updated',
                    'Faculty Member',
                    $faculty->id,
                    'Updated faculty member: ' . $faculty->name
                );

                $message = 'Faculty member updated successfully!';
            } else {
                // Create new faculty
                $faculty = FacultyMember::create($facultyData);

                $this->auditService->log(
                    'faculty_created',
                    'Faculty Member',
                    $faculty->id,
                    'Created faculty member: ' . $faculty->name
                );

                $message = 'Faculty member created successfully!';
            }

            $this->loadData();
            $this->closeFacultyModal();
            $this->successMessage = $message;

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->errorMessage = 'Failed to save faculty member: ' . $e->getMessage();
        }
    }

    public function deleteFaculty($facultyId)
    {
        $this->clearMessages();

        try {
            $faculty = FacultyMember::findOrFail($facultyId);
            $name = $faculty->name;

            // Delete photo if exists
            if ($faculty->photo_path) {
                $photoPath = public_path('experts/' . $faculty->photo_path);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            $faculty->delete();

            $this->auditService->log(
                'faculty_deleted',
                'Faculty Member',
                $facultyId,
                'Deleted faculty member: ' . $name
            );

            $this->loadData();
            $this->successMessage = 'Faculty member deleted successfully!';

        } catch (Exception $e) {
            $this->errorMessage = 'Failed to delete faculty member: ' . $e->getMessage();
        }
    }

    protected function clearMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.admin.website-management');
    }
}
