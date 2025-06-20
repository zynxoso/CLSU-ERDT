# CLSU-ERDT Views Directory Restructure Implementation Plan

## 📋 **Project Overview**

**Project**: CLSU-ERDT Views Directory Restructuring  
**Objective**: Reorganize the Laravel application's views directory for better maintainability, scalability, and developer experience  
**Estimated Duration**: 3-4 weeks  
**Team Size**: 2-3 developers  

---

## 🎯 **Goals & Success Criteria**

### Primary Goals
- ✅ Implement consistent naming conventions across all views
- ✅ Establish clear role-based view separation (Public, Scholar, Admin, Super Admin)
- ✅ Create reusable component architecture
- ✅ Improve code maintainability and developer experience
- ✅ Optimize view loading performance
- ✅ Implement Laravel best practices for view organization

### Success Criteria
- [ ] All views follow consistent naming conventions
- [ ] Zero broken view references after migration
- [ ] Improved page load times (target: 15% improvement)
- [ ] Reduced code duplication by 30%
- [ ] Complete test coverage for all view changes
- [ ] Documentation updated and developer onboarding improved

---

## 🗂️ **Current State Analysis**

### Issues Identified
1. **Inconsistent Naming**: `super_admin/` vs `admin/` naming patterns
2. **Scattered Views**: Profile views exist in multiple locations
3. **Missing Partials**: Large views not broken into components
4. **Duplicate Code**: Similar functionality across different user roles
5. **Poor Organization**: No clear feature-based grouping

### Files Affected
```
Current Structure:
├── about.blade.php (37KB, 601 lines) ⚠️ Too large
├── history.blade.php (29KB, 486 lines) ⚠️ Too large  
├── how-to-apply.blade.php (31KB, 509 lines) ⚠️ Too large
├── dashboard.blade.php (26KB, 380 lines) ⚠️ Too large
├── admin/ (Mixed organization)
├── super_admin/ (Inconsistent naming)
├── scholar/ (Good structure, needs refinement)
├── auth/ (Needs better organization)
├── components/ (Needs expansion)
└── layouts/ (Needs role-specific layouts)
```

---

## 📅 **Implementation Timeline**

### **Phase 1: Planning & Setup** (Week 1)
| Task | Duration | Assignee | Status |
|------|----------|----------|---------|
| Create backup of current views | 1 day | Dev 1 | ⏳ Pending |
| Set up feature branch | 0.5 day | Dev 1 | ⏳ Pending |
| Create new directory structure | 1 day | Dev 2 | ⏳ Pending |
| Update development environment | 0.5 day | Dev 2 | ⏳ Pending |
| Create migration scripts | 2 days | Dev 1 | ⏳ Pending |

### **Phase 2: Core Restructuring** (Week 2)
| Task | Duration | Assignee | Status |
|------|----------|----------|---------|
| Migrate public pages | 2 days | Dev 1 | ⏳ Pending |
| Restructure auth views | 1 day | Dev 2 | ⏳ Pending |
| Create shared components | 2 days | Dev 1 | ⏳ Pending |
| Update layouts structure | 1 day | Dev 2 | ⏳ Pending |

### **Phase 3: Role-Based Views** (Week 2-3)
| Task | Duration | Assignee | Status |
|------|----------|----------|---------|
| Migrate scholar views | 2 days | Dev 1 | ⏳ Pending |
| Migrate admin views | 2 days | Dev 2 | ⏳ Pending |
| Migrate super-admin views | 2 days | Dev 1 | ⏳ Pending |
| Create role-specific components | 1 day | Dev 2 | ⏳ Pending |

### **Phase 4: Advanced Features** (Week 3-4)
| Task | Duration | Assignee | Status |
|------|----------|----------|---------|
| Implement view composers | 1 day | Dev 1 | ⏳ Pending |
| Create custom Blade directives | 1 day | Dev 2 | ⏳ Pending |
| Update route references | 2 days | Dev 1 | ⏳ Pending |
| Update controller references | 2 days | Dev 2 | ⏳ Pending |

### **Phase 5: Testing & Deployment** (Week 4)
| Task | Duration | Assignee | Status |
|------|----------|----------|---------|
| Comprehensive testing | 2 days | All | ⏳ Pending |
| Performance optimization | 1 day | Dev 1 | ⏳ Pending |
| Documentation update | 1 day | Dev 2 | ⏳ Pending |
| Production deployment | 0.5 day | DevOps | ⏳ Pending |

---

## 🏗️ **New Directory Structure**

```
resources/views/
├── app.blade.php                    # Main app layout
├── welcome.blade.php                # Landing page
├── index.blade.php                  # Home page
│
├── public/                          # 🆕 Public marketing pages
│   ├── layouts/
│   │   └── marketing.blade.php
│   ├── pages/
│   │   ├── about.blade.php         # ⬅️ FROM: about.blade.php
│   │   ├── history.blade.php       # ⬅️ FROM: history.blade.php  
│   │   ├── how-to-apply.blade.php  # ⬅️ FROM: how-to-apply.blade.php
│   │   └── faculty.blade.php       # 🆕 New
│   └── partials/                   # 🆕 Break down large files
│       ├── hero-section.blade.php
│       ├── testimonials.blade.php
│       └── contact-info.blade.php
│
├── auth/                           # 🔄 Restructured
│   ├── layouts/
│   │   └── auth.blade.php
│   ├── login/
│   │   ├── admin.blade.php         # ⬅️ FROM: auth/login.blade.php
│   │   └── scholar.blade.php       # ⬅️ FROM: auth/scholar-login.blade.php
│   ├── passwords/                  # ⬅️ FROM: auth/passwords/
│   │   ├── confirm.blade.php
│   │   ├── email.blade.php
│   │   └── reset.blade.php
│   └── verify.blade.php            # ⬅️ FROM: auth/verify.blade.php
│
├── scholar/                        # 🔄 Enhanced structure
│   ├── layouts/
│   │   └── scholar-app.blade.php   # 🆕 Dedicated layout
│   ├── dashboard/
│   │   ├── index.blade.php         # ⬅️ FROM: scholar/dashboard.blade.php
│   │   └── partials/               # 🆕 Break down dashboard
│   │       ├── stats-cards.blade.php
│   │       ├── recent-activity.blade.php
│   │       └── notifications-panel.blade.php
│   ├── profile/                    # 🔄 Consolidated
│   │   ├── index.blade.php         # ⬅️ FROM: scholar/profile.blade.php
│   │   ├── edit.blade.php          # ⬅️ FROM: scholar/profile/edit.blade.php
│   │   └── partials/
│   │       ├── basic-info.blade.php
│   │       ├── academic-info.blade.php
│   │       └── contact-info.blade.php
│   ├── fund-requests/              # ⬅️ FROM: scholar/fund-requests/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── partials/               # 🆕 Component breakdown
│   │       ├── request-form.blade.php
│   │       ├── status-timeline.blade.php
│   │       └── document-upload.blade.php
│   ├── manuscripts/                # ⬅️ FROM: scholar/manuscripts/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── partials/
│   │       ├── manuscript-form.blade.php
│   │       └── submission-status.blade.php
│   ├── documents/                  # ⬅️ FROM: scholar/documents/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── show.blade.php
│   │   └── partials/
│   │       ├── file-upload.blade.php
│   │       └── document-list.blade.php
│   └── settings/                   # 🔄 Consolidated
│       ├── index.blade.php         # ⬅️ FROM: scholar/settings.blade.php
│       ├── password.blade.php      # ⬅️ FROM: scholar/change-password.blade.php
│       └── notifications.blade.php # ⬅️ FROM: scholar/notifications.blade.php
│
├── admin/                          # 🔄 Enhanced structure
│   ├── layouts/
│   │   └── admin-app.blade.php     # 🆕 Dedicated layout
│   ├── dashboard/
│   │   ├── index.blade.php         # ⬅️ FROM: admin/dashboard.blade.php
│   │   ├── cached.blade.php        # ⬅️ FROM: admin/cached-dashboard.blade.php
│   │   └── partials/               # 🆕 Dashboard components
│   │       ├── metrics-cards.blade.php
│   │       ├── charts-section.blade.php
│   │       └── recent-activities.blade.php
│   ├── scholars/                   # ⬅️ FROM: admin/scholars/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── partials/
│   │       ├── scholar-list.blade.php
│   │       ├── scholar-form.blade.php
│   │       └── scholar-stats.blade.php
│   ├── fund-requests/              # ⬅️ FROM: admin/fund-requests/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   ├── partials/
│   │   │   ├── requests-list.blade.php
│   │   │   ├── status-filters.blade.php
│   │   │   └── request-details.blade.php
│   │   └── modals/                 # ⬅️ FROM: admin/fund-requests/modals/
│   │       ├── approve.blade.php
│   │       ├── reject.blade.php
│   │       └── under-review.blade.php
│   ├── manuscripts/                # ⬅️ FROM: admin/manuscripts/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   ├── batch-download.blade.php
│   │   └── partials/
│   │       ├── manuscript-list.blade.php
│   │       ├── manuscript-form.blade.php
│   │       └── status-management.blade.php
│   ├── documents/                  # ⬅️ FROM: admin/documents/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── partials/
│   │       ├── verification-panel.blade.php
│   │       └── document-preview.blade.php
│   ├── reports/                    # ⬅️ FROM: admin/reports/
│   │   ├── index.blade.php
│   │   ├── documents.blade.php
│   │   ├── documents-pdf.blade.php
│   │   ├── funds.blade.php
│   │   ├── funds-pdf.blade.php
│   │   ├── manuscripts.blade.php
│   │   └── manuscripts-pdf.blade.php
│   ├── audit-logs/                 # ⬅️ FROM: admin/audit-logs/
│   │   ├── index.blade.php
│   │   ├── index-livewire.blade.php
│   │   └── show.blade.php
│   ├── analytics/                  # ⬅️ FROM: admin/analytics/
│   │   └── index.blade.php
│   ├── content-management/         # 🆕 Organized content features
│   │   ├── application-timeline/   # ⬅️ FROM: admin/application-timeline/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── important-notes/        # ⬅️ FROM: admin/important-notes/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   └── history/                # ⬅️ FROM: admin/history/
│   │       └── timeline/
│   │           └── index.blade.php
│   ├── profile/                    # ⬅️ FROM: admin/profile.blade.php
│   │   └── edit.blade.php
│   └── settings/                   # ⬅️ FROM: admin/settings/
│       ├── index.blade.php
│       └── password.blade.php      # ⬅️ FROM: admin/change-password.blade.php
│
├── super-admin/                    # 🔄 Renamed from super_admin/
│   ├── layouts/
│   │   └── super-admin-app.blade.php
│   ├── dashboard/
│   │   └── index.blade.php         # ⬅️ FROM: super_admin/dashboard.blade.php
│   ├── user-management/            # 🔄 Consistent naming
│   │   ├── index.blade.php         # ⬅️ FROM: super_admin/user_management.blade.php
│   │   └── edit.blade.php          # ⬅️ FROM: super_admin/edit_user.blade.php
│   ├── system/                     # 🆕 System-related features
│   │   ├── settings.blade.php      # ⬅️ FROM: super_admin/system_settings.blade.php
│   │   ├── configuration.blade.php # ⬅️ FROM: super_admin/system_configuration.blade.php
│   │   └── analytics.blade.php     # ⬅️ FROM: super_admin/analytics.blade.php
│   ├── data-management/            # 🔄 Consistent naming
│   │   └── index.blade.php         # ⬅️ FROM: super_admin/data_management.blade.php
│   ├── website-management/         # 🔄 Consistent naming
│   │   └── index.blade.php         # ⬅️ FROM: super_admin/website_management.blade.php
│   ├── content/                    # 🆕 Content management features
│   │   ├── application-timeline/   # ⬅️ FROM: super_admin/application_timeline*
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   └── announcements/          # 🆕 Announcement management
│   │       └── management.blade.php
│   └── profile/                    # ⬅️ FROM: super_admin/profile/
│       ├── edit.blade.php
│       └── password/
│           └── change.blade.php
│
├── shared/                         # 🆕 Shared components and layouts
│   ├── layouts/
│   │   ├── app.blade.php           # ⬅️ FROM: layouts/app.blade.php
│   │   ├── guest.blade.php         # ⬅️ FROM: layouts/guest-navigation.blade.php
│   │   └── partials/
│   │       ├── header.blade.php
│   │       ├── footer.blade.php
│   │       ├── navigation/
│   │       │   ├── admin.blade.php # ⬅️ FROM: layouts/admin-navigation.blade.php
│   │       │   ├── scholar.blade.php # ⬅️ FROM: layouts/scholar-navigation.blade.php
│   │       │   └── guest.blade.php
│   │       └── sidebar/
│   │           ├── admin.blade.php
│   │           └── scholar.blade.php
│   ├── components/
│   │   ├── ui/                     # 🔄 Enhanced UI components
│   │   │   ├── button.blade.php    # ⬅️ FROM: components/button.blade.php
│   │   │   ├── modal.blade.php     # ⬅️ FROM: components/modal.blade.php
│   │   │   ├── dropdown.blade.php  # ⬅️ FROM: components/dropdown.blade.php
│   │   │   ├── toast.blade.php     # ⬅️ FROM: components/toast.blade.php
│   │   │   └── loading-helper.blade.php # ⬅️ FROM: components/loading-helper.blade.php
│   │   ├── forms/                  # 🆕 Form-specific components
│   │   │   ├── input.blade.php
│   │   │   ├── textarea.blade.php
│   │   │   ├── select.blade.php
│   │   │   └── file-upload.blade.php
│   │   ├── data/                   # 🆕 Data display components
│   │   │   ├── table.blade.php
│   │   │   ├── pagination.blade.php
│   │   │   └── stats-card.blade.php
│   │   └── content/                # 🔄 Content-specific components
│   │       ├── announcements.blade.php # ⬅️ FROM: components/announcements.blade.php
│   │       ├── faculty-expertise.blade.php # ⬅️ FROM: components/faculty-expertise.blade.php
│   │       └── timeline.blade.php
│   └── partials/                   # 🆕 Shared partial views
│       ├── flash-messages.blade.php
│       ├── breadcrumbs.blade.php
│       └── search-filters.blade.php
│
├── livewire/                       # 🔄 Better organized
│   ├── admin/                      # ⬅️ FROM: livewire/admin/
│   │   ├── audit-logs-list.blade.php
│   │   ├── delete-confirmation-modal.blade.php
│   │   ├── manage-scholars.blade.php
│   │   └── user-management.blade.php
│   ├── scholar/                    # ⬅️ FROM: livewire/scholar/
│   │   └── fund-request-filters.blade.php
│   ├── auth/                       # ⬅️ FROM: livewire/auth/
│   │   └── scholar-login.blade.php
│   ├── shared/                     # 🆕 Shared Livewire components
│   │   ├── home-page.blade.php     # ⬅️ FROM: livewire/home-page.blade.php
│   │   └── scholar-fund-request-status.blade.php
│   └── components/                 # 🆕 Reusable Livewire components
│       └── dynamic-search.blade.php
│
├── emails/                         # 🆕 Email templates
│   ├── layouts/
│   │   └── email.blade.php
│   ├── notifications/
│   │   ├── fund-request-status.blade.php
│   │   └── manuscript-status.blade.php
│   └── auth/
│       ├── password-reset.blade.php
│       └── verification.blade.php
│
├── errors/                         # ⬅️ FROM: errors/
│   ├── layouts/
│   │   └── error.blade.php
│   ├── 403.blade.php
│   ├── 404.blade.php
│   ├── 419.blade.php
│   ├── 429.blade.php
│   ├── 500.blade.php
│   └── 503.blade.php
│
└── debug/                          # ⬅️ FROM: debug/ & example/
    └── example/
        └── exceptions.blade.php
```

---

## 🔧 **Technical Implementation**

### **Step 1: Environment Setup**
```bash
# Create feature branch
git checkout -b feature/views-restructure

# Create backup
cp -r resources/views resources/views.backup

# Create new directory structure
mkdir -p resources/views/{public/{layouts,pages,partials},auth/{layouts,login,passwords}}
mkdir -p resources/views/{scholar,admin,super-admin}/{layouts,dashboard,profile,settings}
mkdir -p resources/views/shared/{layouts/partials/{navigation,sidebar},components/{ui,forms,data,content},partials}
mkdir -p resources/views/{livewire/{admin,scholar,auth,shared,components},emails/{layouts,notifications,auth}}
```

### **Step 2: Create Migration Scripts**

**File Migration Script (`migrate-views.php`):**
```php
<?php
/**
 * View Migration Script for CLSU-ERDT
 * Automates the migration of views to new structure
 */

class ViewMigrator {
    private $migrations = [
        // Public pages
        'about.blade.php' => 'public/pages/about.blade.php',
        'history.blade.php' => 'public/pages/history.blade.php',
        'how-to-apply.blade.php' => 'public/pages/how-to-apply.blade.php',
        
        // Auth views
        'auth/login.blade.php' => 'auth/login/admin.blade.php',
        'auth/scholar-login.blade.php' => 'auth/login/scholar.blade.php',
        
        // Scholar views
        'scholar/dashboard.blade.php' => 'scholar/dashboard/index.blade.php',
        'scholar/profile.blade.php' => 'scholar/profile/index.blade.php',
        'scholar/settings.blade.php' => 'scholar/settings/index.blade.php',
        'scholar/change-password.blade.php' => 'scholar/settings/password.blade.php',
        'scholar/notifications.blade.php' => 'scholar/settings/notifications.blade.php',
        
        // Admin views
        'admin/dashboard.blade.php' => 'admin/dashboard/index.blade.php',
        'admin/cached-dashboard.blade.php' => 'admin/dashboard/cached.blade.php',
        'admin/profile.blade.php' => 'admin/profile/edit.blade.php',
        'admin/change-password.blade.php' => 'admin/settings/password.blade.php',
        
        // Super Admin views (rename directory)
        'super_admin/dashboard.blade.php' => 'super-admin/dashboard/index.blade.php',
        'super_admin/user_management.blade.php' => 'super-admin/user-management/index.blade.php',
        'super_admin/edit_user.blade.php' => 'super-admin/user-management/edit.blade.php',
        'super_admin/system_settings.blade.php' => 'super-admin/system/settings.blade.php',
        'super_admin/system_configuration.blade.php' => 'super-admin/system/configuration.blade.php',
        'super_admin/analytics.blade.php' => 'super-admin/system/analytics.blade.php',
        'super_admin/data_management.blade.php' => 'super-admin/data-management/index.blade.php',
        'super_admin/website_management.blade.php' => 'super-admin/website-management/index.blade.php',
        
        // Layouts
        'layouts/app.blade.php' => 'shared/layouts/app.blade.php',
        'layouts/admin-navigation.blade.php' => 'shared/layouts/partials/navigation/admin.blade.php',
        'layouts/scholar-navigation.blade.php' => 'shared/layouts/partials/navigation/scholar.blade.php',
        'layouts/guest-navigation.blade.php' => 'shared/layouts/partials/navigation/guest.blade.php',
        
        // Components
        'components/button.blade.php' => 'shared/components/ui/button.blade.php',
        'components/modal.blade.php' => 'shared/components/ui/modal.blade.php',
        'components/dropdown.blade.php' => 'shared/components/ui/dropdown.blade.php',
        'components/toast.blade.php' => 'shared/components/ui/toast.blade.php',
        'components/loading-helper.blade.php' => 'shared/components/ui/loading-helper.blade.php',
        'components/announcements.blade.php' => 'shared/components/content/announcements.blade.php',
        'components/faculty-expertise.blade.php' => 'shared/components/content/faculty-expertise.blade.php',
    ];
    
    public function migrate() {
        $basePath = resource_path('views');
        
        foreach ($this->migrations as $source => $destination) {
            $sourcePath = $basePath . '/' . $source;
            $destPath = $basePath . '/' . $destination;
            
            if (file_exists($sourcePath)) {
                // Create destination directory if it doesn't exist
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                // Copy file
                copy($sourcePath, $destPath);
                echo "Migrated: {$source} -> {$destination}\n";
            } else {
                echo "Warning: Source file not found: {$source}\n";
            }
        }
    }
    
    public function updateReferences() {
        // Update route references
        $this->updateRouteReferences();
        
        // Update controller references
        $this->updateControllerReferences();
        
        // Update view includes/extends
        $this->updateViewReferences();
    }
    
    private function updateRouteReferences() {
        $routeFiles = [
            'routes/web.php',
            'routes/auth.php',
        ];
        
        $replacements = [
            "view('about')" => "view('public.pages.about')",
            "view('history')" => "view('public.pages.history')",
            "view('how-to-apply')" => "view('public.pages.how-to-apply')",
            "view('auth.login')" => "view('auth.login.admin')",
            "view('auth.scholar-login')" => "view('auth.login.scholar')",
        ];
        
        foreach ($routeFiles as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $content = str_replace(array_keys($replacements), array_values($replacements), $content);
                file_put_contents($file, $content);
                echo "Updated route references in: {$file}\n";
            }
        }
    }
    
    private function updateControllerReferences() {
        $controllerPath = 'app/Http/Controllers';
        $controllers = glob($controllerPath . '/*.php');
        $controllers = array_merge($controllers, glob($controllerPath . '/*/*.php'));
        
        $replacements = [
            "view('admin.dashboard')" => "view('admin.dashboard.index')",
            "view('scholar.dashboard')" => "view('scholar.dashboard.index')",
            "view('super_admin.dashboard')" => "view('super-admin.dashboard.index')",
        ];
        
        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $originalContent = $content;
            $content = str_replace(array_keys($replacements), array_values($replacements), $content);
            
            if ($content !== $originalContent) {
                file_put_contents($controller, $content);
                echo "Updated controller references in: " . basename($controller) . "\n";
            }
        }
    }
    
    private function updateViewReferences() {
        $viewPath = resource_path('views');
        $views = glob($viewPath . '/**/*.blade.php', GLOB_BRACE);
        
        $replacements = [
            "@extends('layouts.app')" => "@extends('shared.layouts.app')",
            "@include('components.button')" => "@include('shared.components.ui.button')",
            "@include('components.modal')" => "@include('shared.components.ui.modal')",
        ];
        
        foreach ($views as $view) {
            $content = file_get_contents($view);
            $originalContent = $content;
            $content = str_replace(array_keys($replacements), array_values($replacements), $content);
            
            if ($content !== $originalContent) {
                file_put_contents($view, $content);
                echo "Updated view references in: " . basename($view) . "\n";
            }
        }
    }
}

// Run migration
$migrator = new ViewMigrator();
$migrator->migrate();
$migrator->updateReferences();

echo "Migration completed!\n";
```

### **Step 3: Create View Composers**

**Create `app/View/Composers/` directory and composers:**

```php
<?php
// app/View/Composers/AdminDataComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\Manuscript;

class AdminDataComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'pendingFundRequests' => FundRequest::where('status', 'pending')->count(),
            'totalScholars' => User::where('role', 'scholar')->count(),
            'pendingManuscripts' => Manuscript::where('status', 'pending')->count(),
        ]);
    }
}
```

```php
<?php
// app/View/Composers/ScholarDataComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ScholarDataComposer
{
    public function compose(View $view): void
    {
        if (Auth::check() && Auth::user()->role === 'scholar') {
            $user = Auth::user();
            $view->with([
                'scholarProfile' => $user->scholarProfile,
                'activeFundRequests' => $user->fundRequests()->whereIn('status', ['pending', 'under_review'])->count(),
                'unreadNotifications' => $user->unreadNotifications()->count(),
            ]);
        }
    }
}
```

**Register composers in `AppServiceProvider`:**
```php
<?php
// app/Providers/AppServiceProvider.php

use App\View\Composers\AdminDataComposer;
use App\View\Composers\ScholarDataComposer;
use Illuminate\Support\Facades\View;

public function boot(): void
{
    // Register view composers
    View::composer(['admin.*', 'super-admin.*'], AdminDataComposer::class);
    View::composer('scholar.*', ScholarDataComposer::class);
    
    // Global data for all views
    View::share('appName', config('app.name'));
    View::share('currentYear', date('Y'));
}
```

### **Step 4: Create Custom Blade Directives**

```php
<?php
// In AppServiceProvider boot() method

use Illuminate\Support\Facades\Blade;

public function boot(): void
{
    // Role-based content directives
    Blade::if('role', function ($role) {
        return auth()->check() && auth()->user()->role === $role;
    });
    
    Blade::if('scholar', function () {
        return auth()->check() && auth()->user()->role === 'scholar';
    });
    
    Blade::if('admin', function () {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']);
    });
    
    // Custom component directive
    Blade::directive('component', function ($expression) {
        return "<?php echo \$__env->make('shared.components.' . {$expression})->render(); ?>";
    });
}
```

### **Step 5: Update Routes**

**Update route view references:**
```php
<?php
// routes/web.php - Updated view references

// Public pages
Route::get('/', function () {
    return view('index'); // Keep as root
})->name('home');

Route::get('/about', function () {
    return view('public.pages.about');
})->name('about');

Route::get('/history', function () {
    return view('public.pages.history');
})->name('history');

Route::get('/how-to-apply', function () {
    return view('public.pages.how-to-apply');
})->name('how-to-apply');

// Auth routes
Route::get('/login', function () {
    return view('auth.login.admin');
})->name('login');

Route::get('/scholar-login', function () {
    return view('auth.login.scholar');
})->name('scholar-login');
```

---

## 🧪 **Testing Strategy**

### **Unit Tests**
```php
<?php
// tests/Unit/ViewStructureTest.php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\View;

class ViewStructureTest extends TestCase
{
    /** @test */
    public function all_public_views_exist()
    {
        $publicViews = [
            'public.pages.about',
            'public.pages.history',
            'public.pages.how-to-apply',
        ];
        
        foreach ($publicViews as $view) {
            $this->assertTrue(View::exists($view), "View {$view} does not exist");
        }
    }
    
    /** @test */
    public function all_auth_views_exist()
    {
        $authViews = [
            'auth.login.admin',
            'auth.login.scholar',
            'auth.passwords.confirm',
            'auth.passwords.email',
            'auth.passwords.reset',
        ];
        
        foreach ($authViews as $view) {
            $this->assertTrue(View::exists($view), "View {$view} does not exist");
        }
    }
    
    /** @test */
    public function all_shared_components_exist()
    {
        $components = [
            'shared.components.ui.button',
            'shared.components.ui.modal',
            'shared.components.ui.dropdown',
            'shared.components.ui.toast',
        ];
        
        foreach ($components as $component) {
            $this->assertTrue(View::exists($component), "Component {$component} does not exist");
        }
    }
}
```

### **Feature Tests**
```php
<?php
// tests/Feature/ViewRenderingTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewRenderingTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function public_pages_render_correctly()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertViewIs('public.pages.about');
        
        $response = $this->get('/history');
        $response->assertStatus(200);
        $response->assertViewIs('public.pages.history');
    }
    
    /** @test */
    public function scholar_dashboard_renders_with_correct_data()
    {
        $scholar = User::factory()->create(['role' => 'scholar']);
        
        $response = $this->actingAs($scholar)->get('/scholar/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('scholar.dashboard.index');
        $response->assertViewHas(['scholarProfile', 'activeFundRequests']);
    }
    
    /** @test */
    public function admin_dashboard_renders_with_correct_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard.index');
        $response->assertViewHas(['pendingFundRequests', 'totalScholars']);
    }
}
```

---

## 📊 **Performance Optimization**

### **View Caching Strategy**
```php
<?php
// In deployment script

// Cache all views for production
php artisan view:cache

// Clear view cache for development
php artisan view:clear
```

### **Component Optimization**
```php
<?php
// Create cached component views for frequently used components

// In AppServiceProvider
public function boot(): void
{
    if (app()->environment('production')) {
        // Pre-cache frequently used components
        View::composer('shared.components.ui.*', function ($view) {
            $view->with('cached', true);
        });
    }
}
```

---

## 📋 **Quality Assurance Checklist**

### **Pre-Migration Checklist**
- [ ] ✅ Backup current views directory
- [ ] ✅ Create feature branch
- [ ] ✅ Set up testing environment
- [ ] ✅ Document current view usage
- [ ] ✅ Create migration scripts

### **During Migration Checklist**
- [ ] ⏳ Follow naming conventions consistently
- [ ] ⏳ Update all route references
- [ ] ⏳ Update all controller references
- [ ] ⏳ Update all view includes/extends
- [ ] ⏳ Test each migrated view
- [ ] ⏳ Validate component functionality

### **Post-Migration Checklist**
- [ ] ⏳ Run full test suite
- [ ] ⏳ Performance testing
- [ ] ⏳ Cross-browser testing
- [ ] ⏳ Mobile responsiveness check
- [ ] ⏳ Accessibility testing
- [ ] ⏳ Documentation update

### **Deployment Checklist**
- [ ] ⏳ Staging environment testing
- [ ] ⏳ Database migration backup
- [ ] ⏳ View cache clearing strategy
- [ ] ⏳ Rollback plan preparation
- [ ] ⏳ Production deployment
- [ ] ⏳ Post-deployment monitoring

---

## 🚨 **Risk Management**

### **Identified Risks**
| Risk | Impact | Probability | Mitigation Strategy |
|------|---------|-------------|-------------------|
| Broken view references | High | Medium | Comprehensive testing + automated reference checking |
| Performance degradation | Medium | Low | Performance testing + view caching |
| User experience disruption | High | Low | Staging environment testing + gradual rollout |
| Development team confusion | Medium | Medium | Clear documentation + training sessions |

### **Rollback Plan**
1. **Immediate Rollback**: Restore from backup if critical issues found
2. **Partial Rollback**: Revert specific problematic views
3. **Reference Fixes**: Quick patches for broken references
4. **Monitoring**: Real-time error monitoring post-deployment

---

## 📚 **Documentation & Training**

### **Developer Documentation**
- [ ] ✅ New directory structure guide
- [ ] ⏳ Component usage documentation
- [ ] ⏳ View composer guidelines
- [ ] ⏳ Custom directive reference
- [ ] ⏳ Testing best practices

### **Training Materials**
- [ ] ⏳ Migration walkthrough video
- [ ] ⏳ New structure overview presentation
- [ ] ⏳ Hands-on workshop materials
- [ ] ⏳ FAQ document
- [ ] ⏳ Troubleshooting guide

---

## 🎯 **Success Metrics**

### **Performance Metrics**
- **Page Load Time**: Target 15% improvement
- **View Compilation Time**: Target 20% reduction
- **Memory Usage**: Target 10% reduction
- **Cache Hit Rate**: Target 85%+ for view cache

### **Code Quality Metrics**
- **Code Duplication**: Target 30% reduction
- **Maintainability Index**: Target score > 80
- **Test Coverage**: Target 90%+ for view-related code
- **Documentation Coverage**: Target 100% for new structure

### **Developer Experience Metrics**
- **Time to Find Views**: Target 50% reduction
- **Onboarding Time**: Target 25% reduction for new developers
- **Development Velocity**: Target 20% improvement in feature delivery

---

## 🔄 **Maintenance & Monitoring**

### **Ongoing Maintenance Tasks**
- [ ] Weekly view performance monitoring
- [ ] Monthly code quality assessment
- [ ] Quarterly structure optimization review
- [ ] Annual architecture evaluation

### **Monitoring Setup**
```php
<?php
// Add view performance monitoring

// In AppServiceProvider
public function boot(): void
{
    if (app()->environment('production')) {
        View::composer('*', function ($view) {
            $start = microtime(true);
            
            $view->with('renderStart', $start);
        });
        
        View::creator('*', function ($view) {
            $renderTime = microtime(true) - $view->renderStart;
            
            // Log slow views
            if ($renderTime > 0.1) {
                Log::warning('Slow view render', [
                    'view' => $view->name(),
                    'time' => $renderTime
                ]);
            }
        });
    }
}
```

---

## 📞 **Support & Communication**

### **Team Communication**
- **Daily Standups**: Progress updates and blocker resolution
- **Weekly Reviews**: Code review sessions and quality checks
- **Milestone Meetings**: Phase completion reviews and next steps
- **Post-Implementation**: Retrospective and lessons learned

### **Stakeholder Updates**
- **Weekly Status Reports**: Progress summary and upcoming milestones
- **Demo Sessions**: Show completed features and improvements
- **Training Sessions**: End-user training on any interface changes
- **Documentation Handoff**: Complete documentation package delivery

---

## ✅ **Conclusion**

This implementation plan provides a comprehensive roadmap for restructuring the CLSU-ERDT views directory. The plan emphasizes:

1. **Systematic Approach**: Phased implementation with clear milestones
2. **Risk Mitigation**: Comprehensive testing and rollback strategies
3. **Performance Focus**: Optimization strategies and monitoring
4. **Team Support**: Training and documentation for smooth transition
5. **Quality Assurance**: Rigorous testing and validation processes

**Expected Outcomes:**
- ✅ Improved code maintainability and developer experience
- ✅ Better performance through optimized view structure
- ✅ Consistent naming conventions and organization
- ✅ Enhanced scalability for future development
- ✅ Reduced technical debt and code duplication

**Next Steps:**
1. Review and approve implementation plan
2. Assign team members to specific phases
3. Set up development environment and tools
4. Begin Phase 1: Planning & Setup

---

*This document will be updated throughout the implementation process to reflect actual progress and any necessary adjustments to the plan.*
