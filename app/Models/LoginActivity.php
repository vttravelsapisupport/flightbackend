<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = ['user_id', 'user_agent', 'ip_address'];
}
