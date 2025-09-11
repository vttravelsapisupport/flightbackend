<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentCreditShellLog extends Model
{
    use HasFactory;
    protected $fillable = ['agent_id','airline_id','book_ticket_id','amount'];
}
