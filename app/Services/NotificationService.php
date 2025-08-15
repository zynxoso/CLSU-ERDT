<?php

namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;
use App\Models\StipendDisbursement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificationMail;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create a notification for a user.
     *
     * @param  int  $userId
     * @param  string  $title
     * @param  string  $message
     * @param  string  $type
     * @param  string|null  $link
     * @param  bool  $sendEmail
     * @return \App\Models\CustomNotification
     */
    public function notify($userId, $title, $message, $type, $link = null, $sendEmail = false)
    {
        $notification = CustomNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'is_read' => false,
            'email_sent' => false,
        ]);

        // Send email if requested
        if ($sendEmail) {
            $this->sendEmailNotification($notification);
        }

        return $notification;
    }

    /**
     * Create notifications for multiple users.
     *
     * @param  array  $userIds
     * @param  string  $title
     * @param  string  $message
     * @param  string  $type
     * @param  string|null  $link
     * @param  bool  $sendEmail
     * @return array
     */
    public function notifyMany(array $userIds, $title, $message, $type, $link = null, $sendEmail = false)
    {
        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = $this->notify($userId, $title, $message, $type, $link, $sendEmail);
        }

        return $notifications;
    }

    /**
     * Send an email notification.
     *
     * @param  \App\Models\CustomNotification  $notification
     * @return bool
     */
    protected function sendEmailNotification(CustomNotification $notification)
    {
        try {
            $user = User::find($notification->user_id);

            if (!$user || !$user->email) {
                return false;
            }

            Mail::to($user->email)->send(new NotificationMail($notification));

            $notification->update(['email_sent' => true]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark a notification as read.
     *
     * @param  int  $notificationId
     * @return bool
     */
    public function markAsRead($notificationId)
    {
        $notification = CustomNotification::find($notificationId);

        if (!$notification) {
            return false;
        }

        $notification->update(['is_read' => true]);

        return true;
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @param  int  $userId
     * @return int
     */
    public function markAllAsRead($userId)
    {
        return CustomNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get unread notifications for a user.
     *
     * @param  int  $userId
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        return CustomNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications count for a user.
     *
     * @param  int  $userId
     * @return int
     */
    public function getUnreadCount($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return 0;
        }
        
        // For scholars, filter by relevant notification types
        if ($user->role === 'scholar') {
            return CustomNotification::where('user_id', $userId)
                ->where('is_read', false)
                ->whereIn('type', [
                    'fund_request',
                    'manuscript',
                    'stipend_notification',
                    'stipend_disbursement',
                    'profile_update',
                    'document'
                ])
                ->count();
        }
        
        // For other users, count all unread notifications
        return CustomNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent notifications for a user.
     *
     * @param  int  $userId
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentNotifications($userId, $limit = 20)
    {
        return CustomNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete old notifications.
     *
     * @param  int  $daysOld
     * @return int
     */
    public function deleteOldNotifications($daysOld = 90)
    {
        $cutoffDate = now()->subDays($daysOld);

        return CustomNotification::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Send stipend disbursement notification to scholar.
     */
    public function sendStipendNotification(StipendDisbursement $disbursement): bool
    {
        try {
            $scholar = $disbursement->scholarProfile;
            $user = $scholar->user;

            // Create in-system notification
            $this->notify(
                $user->id,
                'Stipend Disbursement Notification',
                $this->generateStipendNotificationMessage($disbursement),
                'stipend_disbursement',
                route('scholar.stipends.show', $disbursement->id),
                true // Send email
            );

            // Update notification status
            $disbursement->update([
                'notification_status' => 'sent',
                'notification_sent_at' => Carbon::now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send stipend notification for disbursement {$disbursement->id}: {$e->getMessage()}");
            
            $disbursement->update([
                'notification_status' => 'failed',
            ]);
            
            return false;
        }
    }

    /**
     * Generate stipend notification message.
     */
    private function generateStipendNotificationMessage(StipendDisbursement $disbursement): string
    {
        $amount = number_format($disbursement->amount, 2);
        $period = $disbursement->period_covered;
        $date = $disbursement->disbursement_date->format('F j, Y');
        
        return "Your stipend of ₱{$amount} for {$period} has been processed and is scheduled for disbursement on {$date}. Reference: {$disbursement->reference_number}";
    }

    /**
     * Send bulk notifications for multiple disbursements.
     */
    public function sendBulkStipendNotifications(array $disbursements): array
    {
        $results = [
            'sent' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($disbursements as $disbursement) {
            if ($this->sendStipendNotification($disbursement)) {
                $results['sent']++;
            } else {
                $results['failed']++;
                $results['errors'][] = "Failed to send notification for disbursement {$disbursement->reference_number}";
            }
        }

        return $results;
    }

    /**
     * Send status update notification for stipend disbursement.
     */
    public function sendStipendStatusUpdateNotification(StipendDisbursement $disbursement, string $oldStatus): bool
    {
        try {
            $user = $disbursement->scholarProfile->user;
            
            $this->notify(
                $user->id,
                'Stipend Status Update',
                $this->generateStatusUpdateMessage($disbursement, $oldStatus),
                'stipend_status_update',
                route('scholar.stipends.show', $disbursement->id),
                true // Send email
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send status update notification for disbursement {$disbursement->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Generate status update message.
     */
    private function generateStatusUpdateMessage(StipendDisbursement $disbursement, string $oldStatus): string
    {
        $newStatus = $disbursement->status;
        $reference = $disbursement->reference_number;
        
        return "Your stipend disbursement status has been updated from '{$oldStatus}' to '{$newStatus}'. Reference: {$reference}";
    }

    /**
     * Send reminder notifications for pending disbursements.
     */
    public function sendPendingDisbursementReminders(): array
    {
        $pendingDisbursements = StipendDisbursement::where('status', 'Pending')
            ->where('disbursement_date', '<=', Carbon::now()->addDays(3))
            ->where('notification_status', '!=', 'reminder_sent')
            ->with(['scholarProfile.user'])
            ->get();

        $results = [
            'sent' => 0,
            'failed' => 0,
        ];

        foreach ($pendingDisbursements as $disbursement) {
            try {
                $user = $disbursement->scholarProfile->user;
                
                $this->notify(
                    $user->id,
                    'Stipend Disbursement Reminder',
                    $this->generateReminderMessage($disbursement),
                    'stipend_reminder',
                    route('scholar.stipends.show', $disbursement->id),
                    true // Send email
                );

                // Update notification status
                $disbursement->update([
                    'notification_status' => 'reminder_sent',
                ]);
                
                $results['sent']++;
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for disbursement {$disbursement->id}: {$e->getMessage()}");
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Generate reminder message.
     */
    private function generateReminderMessage(StipendDisbursement $disbursement): string
    {
        $amount = number_format($disbursement->amount, 2);
        $date = $disbursement->disbursement_date->format('F j, Y');
        
        return "Reminder: Your stipend of ₱{$amount} is scheduled for disbursement on {$date}. Reference: {$disbursement->reference_number}";
    }
}
