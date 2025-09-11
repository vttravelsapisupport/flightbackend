<?php

namespace App\Http\Controllers\FlightTicket;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\IntimationRemarks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntimationRemarksController extends Controller
{
    public function submit(Request $request){

        $data = [
            'initimation_id' => $request->remark_id,
            'type' => intval($request->type),
            'remark' => $request->remark,
            'user_id' => Auth::id(),
        ];

        $resp = IntimationRemarks::create($data);

        $request->session()->flash('success','Successfully Saved');

        return redirect()->back();
    }
}
