<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Mail\OTP;
use Carbon\Carbon;
use App\Otp as AppOtp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

class OtpController extends Controller
{
    public static function send(Request $req,$field = ''){
            $validate = Request()->validate([
                'email' => 'required',
            ]);
        $user = User::where('email',$req->email)->pluck('id')->first();
        if($user){
        $otp_value =str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        Mail::to($req->email)->send(new OTP($otp_value));
        $otp = AppOtp::with('user')->whereHas('user',function($q) use($req){
            $q->where('email',$req->email);
        })->first();
        if(!$otp){
            $otp = new AppOtp;
            $otp->user_id = $user;
        }
        $otp->otp = $otp_value;
        $otp->verify = '0';
        $otp->expire = Carbon::now();
        $otp->save();
        return response()->json(['success'=>true,'data'=>'otp has been sent succeesfully'],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function verify(Request $req)
    {
        $validate = Request()->validate([
            'email' => 'required',
            'otp' => 'required',
        ]);
        $otp = AppOtp::orderBy('id','desc')->with('user')->whereHas('user',function($q) use($req){
            $q->where('email',$req->email);
        })->where('otp',$req->otp)->where('verify','0')->first();
        if($otp){
            return response()->json([
                'success' => true,
                'data' => ['email'=>$otp->user->email,'otp'=>$otp->otp],
            ],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Otp or Expire',
            ], 401);
        }
    }
}
