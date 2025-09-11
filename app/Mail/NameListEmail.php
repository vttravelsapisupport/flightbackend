<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NameListEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $ticket_details;
    public $infant_details;
    public function __construct($data,$ticketDetails,$infant_details)
    {
        $this->data = $data;
        $this->infant_details = $infant_details;
        // dd($this->infant_details);
        $this->ticket_details = $ticketDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Name List For '.$this->ticket_details->destination->name.' || '.$this->ticket_details->travel_date->format('d-m-Y').' || PNR-'.$this->ticket_details->pnr)
        ->replyTo('support@vishaltravels.in', 'VishalTravels')
        ->view('emails.namelist.new_email');
    }
}
