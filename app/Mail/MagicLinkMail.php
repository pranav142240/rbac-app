<?php

namespace App\Mail;

use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $magicLinkUrl;
    public $magicLinkModel;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, MagicLink $magicLink)
    {
        $this->user = $user;
        $this->magicLinkModel = $magicLink;
        $this->magicLinkUrl = route('auth.magic-link.verify', ['token' => $magicLink->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Magic Link - Sign in to ' . config('app.name'),
        );
    }    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.magic-link',
            with: [
                'user' => $this->user,
                'magicLink' => $this->magicLinkUrl,
                'expiresAt' => $this->magicLinkModel->expires_at,
                'appName' => config('app.name'),
            ]
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
