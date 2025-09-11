<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\CancellationRequest;
use App\Models\CreditRequest;
use App\Models\DepositRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function pendingDepositRequest(){
        return DepositRequest::where('status',1)->count();
    }


    public function pendingCancelRequest(){
        return CancellationRequest::where('status',1)->count();
    }


    public function pendingCreditRequest(){
        return CreditRequest::where('status',1)->count();
    }


    public function agent(){
        return $this->hasOne('App\Models\FlightTicket\Agent','email','email');
    }


    public function last_login(){
        return $this->hasOne('App\Models\LoginActivity')->orderBy('id','DESC');
    }

}
