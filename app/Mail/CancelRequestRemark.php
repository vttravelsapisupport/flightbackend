<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelRequestRemark extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $subject;
    public $content;
    public $agent;
    public $bookingTicketDetail;
    public $booking_passengers;
    public $heading;

    public function __construct($data,$subject,$content, $agent, $bookingTicketDetail,$booking_passengers, $heading)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->content = $content;
        $this->agent = $agent;
        $this->bookingTicketDetail = $bookingTicketDetail;
        $this->booking_passengers = $booking_passengers;
        $this->heading = $heading;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->replyTo('support@vishaltravels.in','VishalTravels')->markdown('emails.agents.cancel_request_remarks');
    }
}
