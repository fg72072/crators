<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\User;
use App\Otp as AppOtp;
use Illuminate\Http\Request;
use App\Container\CommonContainer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
   
    protected $media;
    public $loginAfterSignUp = true;

    public function __construct(CommonContainer $media){
        return $this->media = $media;

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validate = Request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = User::with('roles')->where('username',$request->username)->first();
        if($user){
            if (Hash::check($request->password, $user->password)) {
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user'=>$user,
                ]);
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], 401);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }


    public function register(Request $request)
    {
        if($request->role == 'doctor'){
            $validate = Request()->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'phone' => 'required',
                'qualification' => 'required',
                // 'id_card_front' => 'required',
                // 'id_card_back' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
        }
        else if($request->role == 'lab'){
            $validate = Request()->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'phone' => 'required',
                'address' => 'required',
                // 'id_card_front' => 'required',
                // 'id_card_back' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
        }
        else if($request->role == 'wholesaler'){
            $validate = Request()->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'phone' => 'required',
                'address' => 'required',
                // 'id_card_front' => 'required',
                // 'id_card_back' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
        }
        else{
            $validate = Request()->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'phone' => 'required',
                'address' => 'required',
                'gender' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if($request->role == 'user'){
            $user->gender = $request->gender;
        }
        if($request->role == 'doctor'){
            $user->qualification = $request->qualification;
        }
        if ($request->hasFile('id_card_front')) {
            $image = $request->file('id_card_front');
            $name  = $this->media->getFileName($image);
            $path  = $this->media->getProfilePicPath('idcard');
            $image->move($path, $name);
            $user->id_card_front = $name;
        }
        if ($request->hasFile('id_card_back')) {
            $image = $request->file('id_card_back');
            $name  = $this->media->getFileName($image);
            $path  = $this->media->getProfilePicPath('idcard');
            $image->move($path, $name);
            $user->id_card_back = $name;
        }
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        if($request->role == 'doctor'){
            $user->assignRole('doctor');
        }
        else if($request->role == 'wholesaler'){
            $user->assignRole('wholesaler');
        }
        else if($request->role == 'retailer'){
            $user->assignRole('retailer');
        }
        else if($request->role == 'lab'){
            $user->assignRole('lab');
        }
        else if($request->role == 'pharmacy'){
            $user->assignRole('pharmacy');
        }
        else{
            $user->assignRole('user');
        }

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'token' => $token,
            'user'=>$user,
        ]);
    }

    public function reset(Request $req)
    {
        $validate = Request()->validate([
            'email' => 'required',
            'otp' => 'required',
        ]);
        $otp = AppOtp::orderBy('id','desc')->with('user.roles')->whereHas('user',function($q) use($req){
            $q->where('email',$req->email);
        })->where('otp',$req->otp)->where('verify','0')->first();
        $token = '';
        if($otp){
            $validate = Request()->validate([
                'password' => 'required|min:8|confirmed',
            ]);
            $otp->user->password = Hash::make($req->password);
            $otp->user->save();
            if (!$token = JWTAuth::fromUser($otp->user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password',
                ], 401);
            }
            else{
                $otp->verify = '1';
                $otp->save();
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $otp->user,
                ],200);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Otp or Expire',
            ], 401);
        }
    }
}
