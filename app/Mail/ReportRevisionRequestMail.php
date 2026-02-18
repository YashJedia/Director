<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportRevisionRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $revisionReason;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, string $revisionReason, string $adminName)
    {
        $this->report = $report;
        $this->revisionReason = $revisionReason;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Revision Requested - ' . $this->report->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report-revision-request',
            with: [
                'report' => $this->report,
                'revisionReason' => $this->revisionReason,
                'adminName' => $this->adminName,
            ],
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
