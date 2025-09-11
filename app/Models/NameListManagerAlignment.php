<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameListManagerAlignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'sector_id',
        'airline_id',
        'user_id'
    ];
}
