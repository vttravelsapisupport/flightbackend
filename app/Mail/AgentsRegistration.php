<?php

namespace App\Mail;

use App\Models\FlightTicket\Agent;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentsRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $agent;
    public $user;
    public $password;

    public function __construct(Agent $agent,User $user,$password)
    {
        $this->user  = $user;
        $this->agent = $agent;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo('support@vishaltravels.in', 'VishalTravels')
        ->markdown('emails.agents-distributors.registration');
    }
}
