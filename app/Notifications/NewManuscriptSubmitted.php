<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Manuscript;

class NewManuscriptSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $manuscript;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Manuscript  $manuscript
     * @return void
     */
    public function __construct(Manuscript $manuscript)
    {
        $this->manuscript = $manuscript;

        // Store notification in custom notifications table
        $scholar = $this->manuscript->scholarProfile->user;
        $notificationService = app(\App\Services\NotificationService::class);

        // Store in custom notifications for all admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->hasManuscriptNotifications()) {
                $notificationService->notify(
                    $admin->id,
                    'New Manuscript Submitted',
                    "Scholar {$scholar->name} has submitted a new manuscript titled \"{$this->manuscript->title}\"",
                    'NewManuscriptSubmitted',
                    route('admin.manuscripts.show', $this->manuscript->id),
                    false // Don't send email here as it's handled by Laravel's notification system
                );
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database'];

        // Add email if admin has email notifications enabled
        if ($notifiable->email_notifications && $notifiable->manuscript_notifications) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $scholar = $this->manuscript->scholarProfile->user;

        return (new MailMessage)
            ->subject('New Manuscript Submitted - ' . $this->manuscript->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new manuscript has been submitted and requires your review.')
            ->line('**Scholar:** ' . $scholar->name)
            ->line('**Title:** ' . $this->manuscript->title)
            ->line('**Type:** ' . $this->manuscript->manuscript_type)
            ->line('**Status:** ' . $this->manuscript->status)
            ->action('Review Manuscript', route('admin.manuscripts.show', $this->manuscript->id))
            ->line('Please review this manuscript at your earliest convenience.')
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $scholar = $this->manuscript->scholarProfile->user;

        return [
            'manuscript_id' => $this->manuscript->id,
            'scholar_name' => $scholar->name,
            'scholar_id' => $scholar->id,
            'title' => $this->manuscript->title,
            'manuscript_type' => $this->manuscript->manuscript_type,
            'status' => $this->manuscript->status,
            'submitted_at' => $this->manuscript->created_at,
            'action_url' => route('admin.manuscripts.show', $this->manuscript->id),
        ];
    }

    /**
     * Get the notification's database type.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function databaseType($notifiable)
    {
        return 'new_manuscript';
    }
}
