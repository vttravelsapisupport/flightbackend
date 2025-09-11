<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function index() {
        $settings = AppSetting::all();
        return view('settings.app.index',compact('settings'));
    }


    public function updateStatus(Request $request) {
        
        $settings         = AppSetting::find($request->id);
        $settings->status = $request->status == 'true' ? 1 : 0;
        $settings->save();

        if($request->status == 'true')
            return response()->json(['activated' => true, 'message' => 'Activated successfully']);
        else
            return response()->json(['activated' => false, 'message' => 'Deactivated successfully']);
    }
}
