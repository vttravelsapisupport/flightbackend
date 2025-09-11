<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'settings_name',
        'settings_code',
        'status'
    ];
}
