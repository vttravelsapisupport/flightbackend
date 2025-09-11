<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgentWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $agent;
    public $user;
    public $token;
    public $password;

    public function __construct($agent,$user,$token,$password)
    {
        $this->agent = $agent;
        $this->user  = $user;
        $this->token  = $token;
        $this->password = $password;
      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo('support@vishaltravels.in','VishalTravels')->markdown('emails.agent.welcome');
    }
}
