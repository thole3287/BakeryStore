<?php

namespace App\Mail;

use App\Models\Bill_detail;
use App\Models\Bills;
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
    public $bill;

    /**
     * Create a new message instance.
     */
    public function __construct(Bills $bill)
    {
        $this->bill = $bill;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Cancelled',
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
    public function build()
    {
        // return $this->view('emails.cancel-order')
        //             ->with([
        //                 'customer' => $this->bill->customer,
        //                 'items' => $this->bill->items,
        //             ]);
        $billDetail = Bill_detail::where('id_bill', '=', $this->bill->id)->get();
        $items = [];
        foreach ($billDetail as $detail) {
            $product = Products::find($detail->id_product);
            $items[] = [
                'product' => $product,
                'quantity' => $detail->quantity
            ];
        }
        $customer = $this->bill->customer;

        if (!$items || !$customer) {
            return $this->text('There was an error canceling the order.');
        }

        return $this->view('emails.cancel-order', [
            'items' => $items,
            'customer' => $customer
        ]);

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
