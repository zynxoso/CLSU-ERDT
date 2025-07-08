<?php

namespace App\Mail;

use App\Models\CustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The notification instance.
     *
     * @var \App\Models\CustomNotification
     */
    public $notification;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\CustomNotification  $notification
     * @return void
     */
    public function __construct(CustomNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->notification->title,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'title' => $this->notification->title,
                'message' => $this->notification->message,
                'link' => $this->notification->link,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}