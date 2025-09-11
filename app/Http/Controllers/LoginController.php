<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Models\SMSOTP;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\LoginActivity;


class LoginController extends Controller

{
    public function sendgridipwhitelist(Request $request){
        $ip  = $request->ip;

        $apiKey = '';
        $sg = new \SendGrid($apiKey);
        $request_body = json_decode('{
            "ips": [
                {
                    "ip":  "'.$ip.'"
                }

            ]
        }');

        try {
            $response = $sg->client->access_settings()->whitelist()->post($request_body);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (\Exception $ex) {
            echo 'Caught exception: '.  $ex->getMessage();
        }

    }

    public function getSendgridWhitelistIP(Request $request){
        $ip  = $request->ip;

        $apiKey = '';
        $sg = new \SendGrid($apiKey);

        try {
            $response = $sg->client->access_settings()->whitelist()->get();
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (\Exception $ex) {
            echo 'Caught exception: '.  $ex->getMessage();
        }

    }

    public function showLoginPage(){
        if(Auth::check()){
            return redirect(route('dashboard'));
        }else{
            return view('auth.login');
        }
    }

    public function logout(Request $request){
        $request->session()->flash('success','Successfully Logged out !');
        Auth::logout();
        return redirect(route('login'));
    }

    public function submitLoginPage(Request $request){
        $email_input = $request->email;
        $field =  filter_var($email_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $this->validate($request,[
            'email' => ['required'],
            'password' => ['required']
        ]);
        $data = [
            $field => $request->email,
            'password' => $request->password,
            'status' => 1
        ];




        $user = User::where($field,$request->email)->first();
        if(!$user){
            return redirect()->back()->withErrors([
                'message' => 'Invalid username and password combination !'
            ]);
        }
        $isAuth =  Hash::check($request->password, $user->password);
        $currentRole =  $user->hasAnyRole(['administrator', 'manager', 'staff', 'b2c', 'accounts','marketing']);

        if ($isAuth && $currentRole) {
            LoginActivity::create([
                'user_id' => $user->id,
                'user_agent' => $request->header('user-agent'),
                'ip_address' => $request->ip()
            ]);
            Auth::login($user);
            return redirect()->intended('dashboard');

            // $excluded_user= [
            //     '9832500105',
            //     '9050403081',
            //     '9749497494',
            //     '9933053000',
            //     '9933057000',
            //     'amit@tripfactory.com',
            //     'vinay@tripfactory.com'
            // ];

            // if(in_array($email_input, $excluded_user)){
            //     Auth::login($user);
            //     return redirect()->intended('dashboard');
            // }
            // $request->session()->flash('showOtpPage',true);
            // $request->session()->put('otpUserID', $user->id);

            // return redirect(route('mfa'));
        }else{
            $request->session()->flash('error','Invalid username and password combination !');
            return back()->onlyInput('email');
        }

    }
    public function showMFAPage(Request $request){
        $validRequest=    $request->session()->get('showOtpPage');
        $otpUserID=    $request->session()->get('otpUserID');

       if($validRequest === true){
           $user = User::where('id', $otpUserID)->firstOrFail();

           $phone = "9832500105"; // deepankar
           $phone1 = "7478631036"; // debarshi
           if(env('APP_ENV') == 'local'){
               $phone = "9832500105"; // deepankar
               $phone1 = "7478631036"; // debarshi
           }elseif(env('APP_ENV') == 'production'){
//               $phone = "9800940000"; // deepankar
//               $phone1 = "9749497494"; // debarshi
               $phone = "9800940000"; // ankit
               $phone1 = "9749497494"; // poonam
           }

           $otp = rand(1000,9999);
           $request->session()->flash('otp', $otp);
//           $message = 'Dear '.$user->first_name.'
//Your OTP is '. $otp.'
//
//Regards
//GOFLYS';
//           $resp = SMSService::sendSMS($phone,$message);
//           $resp1 = SMSService::sendSMS($phone1,$message);

            $resp = WhatsappService::sendOTP($phone,$user->first_name,$otp);
            $resp = WhatsappService::sendOTP($phone1,$user->first_name,$otp);

           // save in the DB

           SMSOTP::create([
               'user_id' => $user->id,
               'otp' => $otp,
               'ip' => $request->ip(),
               'user_agents' =>$request->header('user-agent')
           ]);
           $request->session()->flash('otp',$otp);
           $request->session()->flash('success',"Please enter the OTP sent to ".$phone. ' and '. $phone1);
           return view('auth.mfa');
       }elseif($validRequest == 1){
           return view('auth.mfa');
       } else{
           $request->session()->flash('error','Invalid Request. Please login below!');
           return redirect(route('login'));

       }
    }
    public function submitMFAPage(Request $request){

        $this->validate($request,[
            'otp' => ['required'],
            'g-recaptcha-response' =>
                ['required', new Recaptcha()]
        ]);


        $otp     = $request->session()->get('otp');
        $user_id = $request->session()->get('otpUserID');


        if($request->otp == $otp)
        {
            $resp = SMSOTP::where('otp',$otp)
                ->where('user_id',$user_id)
//                ->where('ip',$request->ip())
//                ->where('user_agents',$request->header('user-agent'))
                ->firstorFail();


            if(!$resp)
                abort(403,'Invalid OTP');


            $now  = Carbon::now();
            $difference = $resp->created_at->diffInSeconds($now);

            if($difference >= 300){
                abort(403,'OTP Expired');
            }
            $user = User::where('id',$user_id)->where('status',1)->first();
            if($user){
                $request->session()->regenerate();
                Auth::login($user);
            }else{
                Auth::logout();
                return redirect()->intended('dashboard');
            }

           return redirect()->intended('dashboard');
        }else{
            $request->session()->flash('showOtpPage',1);
            $request->session()->flash('error','Invalid OTP!');
            return redirect(route('mfa'));
        }
    }
}
