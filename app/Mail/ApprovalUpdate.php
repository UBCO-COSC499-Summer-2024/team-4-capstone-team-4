<?php

namespace App\Mail;

use App\Models\Approval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalUpdate extends Mailable {
    use Queueable, SerializesModels;
    public $approval;

    /**
     * Create a new message instance.
     */
    public function __construct(Approval $approval) {
        $this->approval = $approval;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'insightatubc@gmail.com'), env('MAIL_FROM_NAME', 'Insight')),
            subject: config('app.name').' - Approval Update',
            cc: ['clementan25@gmail.com'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            markdown: 'emails.approval-update',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        return [];
    }
}
