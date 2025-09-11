<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupplierBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $bookTicketDetails;
    public $passengerDetails;

    public function __construct($bookTicketDetails, $passengerDetails)
    {
        $this->passengerDetails = $passengerDetails;
        $this->bookTicketDetails = $bookTicketDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.suppliers.supplier-confirmation')
        ->replyTo('support@vishaltravels.in', 'VishalTravels')
            ->subject('Booking Confirmation - ' . $this->bookTicketDetails->bill_no);
    }
}
