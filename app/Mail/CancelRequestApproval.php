<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelRequestApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void 
     */

    public $cancelRequest;
    public $subject;
    public $message;
    public $refundData;
    public $agent;
    public $bookingTicketDetail;
    public $booking_passengers;

    public function __construct($agent, $cancelRequest, $subject, $message, $refundData, $bookingTicketDetail, $booking_passengers)
    {
        $this->agent = $agent;
        $this->cancelRequest = $cancelRequest;
        $this->subject = $subject;
        $this->message = $message;
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
        return $this->subject($this->subject)->replyTo('support@vishaltravels.in','VishalTravels')->markdown('emails.agents.cancel_request_approval');
    }
}
