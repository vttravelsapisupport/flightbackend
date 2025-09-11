<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierCancelRequestApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public $subject;
    public $refundData;
    public $owner;
    public $bookingTicketDetail;
    public $booking_passengers;

    public function __construct($owner,  $subject, $refundData, $bookingTicketDetail, $booking_passengers)
    {
        $this->owner = $owner;
        $this->subject = $subject;
        $this->refundData = $refundData;
        $this->bookingTicketDetail = $bookingTicketDetail;
        $this->booking_passengers = $booking_passengers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->replyTo('support@vishaltravels.in', 'VishalTravels')->markdown('emails.suppliers.cancel_request_approval');
    }
}
