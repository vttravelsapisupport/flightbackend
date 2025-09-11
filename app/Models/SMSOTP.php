<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSOTP extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','otp','ip','user_agents'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
