<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirportAlignment extends Model
{
    use HasFactory;

    protected $fillable = ['airport_code','airport_align','status'];

    
}
