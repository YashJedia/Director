<?php

namespace App\Mail;

use App\Models\AdminInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $invitationLink;

    /**
     * Create a new message instance.
     */
    public function __construct(AdminInvitation $invitation, string $invitationLink)
    {
        $this->invitation = $invitation;
        $this->invitationLink = $invitationLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Invitation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-invitation',
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

