<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesNote extends Model
{
    use HasFactory;
    protected $fillable = ['book_ticket_id','user_id','user_id','notes','isActive'];
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
