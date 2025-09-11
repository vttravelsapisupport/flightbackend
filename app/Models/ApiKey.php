<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;
    protected $table = 'api_keys';

    protected $fillable = [
        'agent_id',
        'agent_code',
        'product_id',
        'subscription_id',
        'api_key_primary',
        'api_key_secondary',
        'external_status',
        'external_start_date',
        'external_end_date',
        'external_expiration_date',
        'status',
    ];
}
