<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Voucher;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateOrder extends Mailable
{
    use Queueable, SerializesModels;

    private int|string $idOrder;
    private OrderServiceInterface $orderService;

    /**
     * Create a new message instance.
     */
    public function __construct(  int|string $idOrder)
    {

        $this->idOrder = $idOrder;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'),
            subject: __('messages.mail.order-success.create_message_title'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $order = Order::where('id' , $this->idOrder)->with(["orderDetails", 'orderHistory', 'user', 'orderDetails.variation', 'orderDetails.product', 'voucher'])->first();
        if(isset($order->voucher_id))$voucher = Voucher::where('id', $order->voucher_id)->first();
        return new Content(
            view: 'mail.order-success',
            with: [
                'order' =>$order,
                'voucher'=>$voucher ?? ''
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
