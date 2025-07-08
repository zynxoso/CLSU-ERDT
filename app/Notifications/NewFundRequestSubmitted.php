<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FundRequest;
use App\Models\User;
use App\Services\NotificationService;

class NewFundRequestSubmitted extends Notification
{
    use Queueable;

    protected $fundRequest;
    protected $notificationService;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\FundRequest  $fundRequest
     * @return void
     */
    public function __construct(FundRequest $fundRequest)
    {
        $this->fundRequest = $fundRequest;
        $this->notificationService = app(NotificationService::class);

        // Get the scholar information
        $scholar = $this->fundRequest->scholarProfile->user;

        // Store in custom notifications for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->hasFundRequestNotifications()) {
                $this->notificationService->notify(
                    $admin->id,
                    'New Fund Request Submitted',
                    "Scholar {$scholar->name} has submitted a new fund request for {$this->fundRequest->purpose} amounting to ₱" . number_format($this->fundRequest->amount, 2),
                    'NewFundRequestSubmitted',
                    route('admin.fund-requests.show', $this->fundRequest->id),
                    true // Send email notification
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
        return []; // Don't use Laravel's default notification system
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return null; // Email is handled by NotificationService
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return []; // Data is handled by NotificationService
    }
}
