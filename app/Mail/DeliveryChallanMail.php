<?php

namespace App\Mail;

use App\Models\DeliveryChallan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class DeliveryChallanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $challan;
    public $pdfPath;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(DeliveryChallan $challan, $pdfPath, $customMessage = null)
    {
        $this->challan = $challan;
        $this->pdfPath = $pdfPath;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Delivery Challan #' . $this->challan->challan_number . ' - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.delivery-challan',
            with: [
                'challan' => $this->challan,
                'customMessage' => $this->customMessage,
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
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Delivery-Challan-' . $this->challan->challan_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}