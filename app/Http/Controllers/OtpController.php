<?php

namespace App\Http\Controllers;

use App\Models\SMSOTP;
use Carbon\Carbon;

class OtpController extends Controller
{

    public function __construct() {
        $this->middleware('permission:otp show', ['only' => ['index']]);
    }

    public function index() {
        $otps = SMSOTP::whereDate('created_at', Carbon::now())->orderBy("id", 'DESC')->get();
        foreach($otps as $otp) {
            $now  = Carbon::now();
            $difference = $otp->created_at->diffInSeconds($now);
            $otp->difference = $difference;
        }

        return view('moderation.otps.index', compact('otps'));
    }
}
