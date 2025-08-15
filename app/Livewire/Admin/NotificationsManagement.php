<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\AuditService;
use App\Models\CustomNotification;

class NotificationsManagement extends Component
{
    use WithPagination;

    // UI State
    public $successMessage = '';
    public $errorMessage = '';

    // Filters
    public $filterType = '';
    public $filterStatus = '';
    public $search = '';

    protected $auditService;

    protected $queryString = [
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'search' => ['except' => '']
    ];

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        // Initialize component
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($notificationId)
    {
        $this->clearMessages();

        try {
            $user = Auth::user();

            // Allow admins to mark any admin notification; others can only mark their own
            if (in_array($user->role, ['admin', 'super_admin'])) {
                $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
                $notification = CustomNotification::where('id', $notificationId)
                    ->whereIn('user_id', $adminUserIds)
                    ->where('is_read', false)
                    ->first();
            } else {
                $notification = CustomNotification::where('id', $notificationId)
                    ->where('user_id', $user->id)
                    ->where('is_read', false)
                    ->first();
            }

            if (!$notification) {
                $this->errorMessage = 'Notification not found or already read.';
                return;
            }

            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            $this->auditService->log(
                'notification_marked_read',
                'CustomNotification',
                $notificationId,
                'Marked notification as read'
            );

            $this->successMessage = 'Notification marked as read.';

        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to mark notification as read: ' . $e->getMessage();
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->clearMessages();

        try {
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'super_admin'])) {
                // Admin: mark all admin notifications as read for specific types
                $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');

                $unreadCount = CustomNotification::whereIn('user_id', $adminUserIds)
                    ->where('is_read', false)
                    ->whereIn('type', [
                        'NewFundRequestSubmitted',
                        'NewManuscriptSubmitted',
                        'fund_request',
                        'manuscript',
                        'stipend_notification'
                    ])->count();

                if ($unreadCount === 0) {
                    $this->errorMessage = 'No unread notifications found.';
                    return;
                }

                CustomNotification::whereIn('user_id', $adminUserIds)
                    ->where('is_read', false)
                    ->whereIn('type', [
                        'NewFundRequestSubmitted',
                        'NewManuscriptSubmitted',
                        'fund_request',
                        'manuscript',
                        'stipend_notification'
                    ])
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);

                $this->auditService->log(
                    'all_notifications_marked_read',
                    'CustomNotification',
                    null,
                    "Marked {$unreadCount} admin notifications as read"
                );

                $this->successMessage = "All {$unreadCount} admin notifications marked as read.";
            } else {
                // Non-admin: mark own notifications
                $unreadCount = CustomNotification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                if ($unreadCount === 0) {
                    $this->errorMessage = 'No unread notifications found.';
                    return;
                }

                CustomNotification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);

                $this->auditService->log(
                    'all_notifications_marked_read',
                    'CustomNotification',
                    null,
                    "Marked {$unreadCount} notifications as read"
                );

                $this->successMessage = "All {$unreadCount} notifications marked as read.";
            }

        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to mark all notifications as read: ' . $e->getMessage();
        }
    }

    /**
     * Apply filters and reset pagination
     */
    public function applyFilters()
    {
        $this->resetPage();
    }

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterStatus = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Clear success and error messages
     */
    protected function clearMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    /**
     * Get notifications with filters and pagination
     */
    protected function getNotifications()
    {
        $user = Auth::user();

        // For admin and super_admin, show notifications for all admin users
        if (in_array($user->role, ['admin', 'super_admin'])) {
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            $query = CustomNotification::whereIn('user_id', $adminUserIds)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ]);
        } else {
            $query = CustomNotification::where('user_id', $user->id);
        }

        $query->when($this->filterType, function ($query) {
                return $query->where('type', $this->filterType);
            })
            ->when($this->filterStatus === 'read', function ($query) {
                return $query->where('is_read', true);
            })
            ->when($this->filterStatus === 'unread', function ($query) {
                return $query->where('is_read', false);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('message', 'like', '%' . $this->search . '%');
                });
            });

        return $query->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    /**
     * Get unread notifications count
     */
    protected function getUnreadCount()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            return CustomNotification::whereIn('user_id', $adminUserIds)
                ->where('is_read', false)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ])
                ->count();
        }

        return CustomNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.notifications-management', [
            'notifications' => $this->getNotifications(),
            'unreadCount' => $this->getUnreadCount()
        ]);
    }
}
