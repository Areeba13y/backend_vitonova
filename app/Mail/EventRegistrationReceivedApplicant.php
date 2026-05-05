<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationReceivedApplicant extends Mailable
{
    use Queueable, SerializesModels;

    public UserEvent $registration;
    public Event $event;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(UserEvent $registration, Event $event, User $user)
    {
        $this->registration = $registration;
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We have received your event registration',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-registration-received-applicant',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
