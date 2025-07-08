<?php

namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificationMail;

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
}
