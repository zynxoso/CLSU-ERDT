<?php

namespace App\Livewire\Scholar;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\CustomNotification;

class Notifications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public $filterType = '';
    public $filterRead = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterRead' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function mount()
    {
        // Initialize component
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterRead()
    {
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {   
        try {
            $notification = CustomNotification::where('id', $notificationId)
                ->where('user_id', Auth::id())
                ->first();

            if ($notification && !$notification->is_read) {
                $notification->update(['is_read' => true, 'read_at' => now()]);
                
                $this->dispatch('notification-updated');
                session()->flash('success', 'Notification marked as read.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark notification as read.');
        }
    }

    public function markAllAsRead()
    {
        try {
            $updated = CustomNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

            if ($updated > 0) {
                $this->dispatch('notification-updated');
                session()->flash('success', "Marked {$updated} notifications as read.");
            } else {
                session()->flash('info', 'No unread notifications to mark.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark all notifications as read.');
        }
    }

    public function deleteNotification($notificationId)
    {
        try {
            $notification = CustomNotification::where('id', $notificationId)
                ->where('user_id', Auth::id())
                ->first();

            if ($notification) {
                $notification->delete();
                session()->flash('success', 'Notification deleted successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete notification.');
        }
    }

    public function render()
    {
        $query = CustomNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        // Apply type filter
        if (!empty($this->filterType)) {
            $query->where('type', $this->filterType);
        }

        // Apply read status filter
        if ($this->filterRead !== '') {
            $query->where('is_read', $this->filterRead === '1');
        }

        $notifications = $query->paginate($this->perPage);

        // Get notification types for filter dropdown
        $notificationTypes = CustomNotification::where('user_id', Auth::id())
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        // Get unread count
        $unreadCount = CustomNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('livewire.scholar.notifications', [
            'notifications' => $notifications,
            'notificationTypes' => $notificationTypes,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'profile_update' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'fund_request' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'manuscript' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            default => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
        };
    }

    public function getNotificationColor($type)
    {
        return match($type) {
            'profile_update' => 'secondary',
            'fund_request' => 'primary',
            'document' => 'purple',
            'manuscript' => 'indigo',
            default => 'gray'
        };
    }

    public function getNotificationLabel($type)
    {
        return match($type) {
            'profile_update' => 'Profile Update',
            'fund_request' => 'Fund Request',
            'document' => 'Document',
            'manuscript' => 'Manuscript',
            'stipend_notification' => 'Stipend',
            'stipend_disbursement' => 'Stipend Disbursement',
            default => 'General'
        };
    }
}