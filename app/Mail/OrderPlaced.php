<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;
use App\Models\Bills;
use App\Models\Bill_Detail;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $bill;
    public $items;

    public function __construct(Customer $customer, Bills $bill, $items)
    {
        $this->customer = $customer;
        $this->bill = $bill;
        $this->items = $items; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Placed Confirmation',
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
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->view('emails.orderPlaced')
                    ->with([
                        'customer' => $this->customer,
                        'bill' => $this->bill,
                        'items' => $this->items
                    ]);
    }
}
