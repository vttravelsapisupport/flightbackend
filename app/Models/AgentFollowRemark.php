<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentFollowRemark extends Model
{
    use HasFactory;
    protected $fillable = ['agent_id', 'user_id', 'remarks'];

    public function agent()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
}