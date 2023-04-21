<?php

namespace App\Mail;

use App\Models\Bill_detail;
use App\Models\Bills;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelOrder extends Mailable
{
    use Queueable, SerializesModels;
    public $customer;
    public $items;

    public $bill;

    /**
     * Create a new message instance.
     */
    public function __construct(Customer $customer, $items, Bills $bill)
    {
        $this->customer = $customer;
        $this->items = $items;
        $this->bill = $bill;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Cancellation Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }    subject('Order Cancellation Confirmation')
    public function build()
    {
        return $this->view('emails.canceled_products');

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
