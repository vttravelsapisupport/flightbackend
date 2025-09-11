<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleRep extends Model
{
    protected $fillable = ['name','email','phone','password','balance','status'];

    public function agent_alignment(){
        return $this->hasMany('App\Models\SaleRepAgentAlignment','sales_rep_id','id');
    }
}

