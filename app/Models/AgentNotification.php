<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentNotification extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'body',
        'notification_type',
        'notification_level',
        'created_by',
        'status'
    ];
}
