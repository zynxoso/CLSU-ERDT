<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\FundRequest;

class FundRequestStatusChanged extends Notification
{
    use Queueable;

    protected $fundRequest;
    protected $oldStatus;
    protected $newStatus;
    protected $notes;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\FundRequest  $fundRequest
     * @param  string  $oldStatus
     * @param  string  $newStatus
     * @param  string|null  $notes
     * @return void
     */
    public function __construct(FundRequest $fundRequest, $oldStatus, $newStatus, $notes = null)
    {
        $this->fundRequest = $fundRequest;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->notes = $notes;

        // Store notification in custom notifications table
        $this->storeCustomNotification();
    }

    /**
     * Store the notification in the custom notifications table.
     *
     * @return void
     */
    protected function storeCustomNotification()
    {
        // Load the scholar profile and user relationships
        $this->fundRequest->load('scholarProfile.user');

        $user = $this->fundRequest->scholarProfile?->user;
        if (!$user) {
            Log::warning('FundRequestStatusChanged: Unable to find user for fund request', [
                'fund_request_id' => $this->fundRequest->id,
                'scholar_profile_id' => $this->fundRequest->scholar_profile_id
            ]);
            return;
        }

        $title = 'Fund Request Status Update';
        $message = 'Your fund request for "' . $this->fundRequest->purpose . '" has been updated from ' .
                   $this->oldStatus . ' to ' . $this->newStatus;

        if ($this->notes) {
            $message .= "\n\nAdmin Notes: " . $this->notes;
        }

        // Create custom notification using the NotificationService
        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notify(
            $user->id,
            $title,
            $message,
            'fund_request',
            route('scholar.fund-requests.show', $this->fundRequest->id),
            false // Email is handled by Laravel notification system
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Only use database channel, removing mail channel
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Fund Request Status Update: ' . $this->fundRequest->purpose)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The status of your fund request for "' . $this->fundRequest->purpose . '" has been updated.')
            ->line('Previous status: ' . $this->oldStatus)
            ->line('New status: ' . $this->newStatus);

        if ($this->notes) {
            $message->line('Admin notes:')
                    ->line($this->notes);
        }

        // Add data privacy reminder for approved requests
        if ($this->newStatus === 'Approved') {
            $message->line('')
                    ->line('IMPORTANT DATA PRIVACY REMINDER:')
                    ->line('As your request has been approved, please remember that all financial information and documents submitted are subject to our data privacy policy. This information will be used solely for processing your disbursement and for audit purposes.')
                    ->line('Please review the complete data privacy policy in your fund request details page.');
        }

        $message->action('View Fund Request', route('scholar.fund-requests.show', $this->fundRequest->id))
                ->line('Thank you for using our platform!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'fund_request_id' => $this->fundRequest->id,
            'fund_request_purpose' => $this->fundRequest->purpose,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'amount' => $this->fundRequest->amount,
            'action_url' => route('scholar.fund-requests.show', $this->fundRequest->id),
        ];

        if ($this->notes) {
            $data['notes'] = $this->notes;
        }

        return $data;
    }
}
