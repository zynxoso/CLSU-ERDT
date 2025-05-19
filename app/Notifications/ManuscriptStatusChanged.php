<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Manuscript;

class ManuscriptStatusChanged extends Notification
{
    use Queueable;

    protected $manuscript;
    protected $oldStatus;
    protected $newStatus;
    protected $notes;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Manuscript  $manuscript
     * @param  string  $oldStatus
     * @param  string  $newStatus
     * @param  string|null  $notes
     * @return void
     */
    public function __construct(Manuscript $manuscript, $oldStatus, $newStatus, $notes = null)
    {
        $this->manuscript = $manuscript;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->notes = $notes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject('Manuscript Status Update: ' . $this->manuscript->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The status of your manuscript "' . $this->manuscript->title . '" has been updated.')
            ->line('Previous status: ' . $this->oldStatus)
            ->line('New status: ' . $this->newStatus);

        if ($this->notes) {
            $message->line('Admin notes:')
                    ->line($this->notes);
        }

        $message->action('View Manuscript', route('scholar.manuscripts.show', $this->manuscript->id))
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
            'manuscript_id' => $this->manuscript->id,
            'manuscript_title' => $this->manuscript->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];

        if ($this->notes) {
            $data['notes'] = $this->notes;
        }

        return $data;
    }
}
