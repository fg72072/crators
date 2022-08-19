<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Container\CommonContainer;

class ProfileController extends Controller
{
    protected $media;

    public function __construct(CommonContainer $media){
        return $this->media = $media;

    }
    public function changePassword(Request $req)
    {
            $validate = Request()->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed'
            ]);
            if (Hash::check($req->current_password, JWTAuth::user()->password)) {
                JWTAuth::user()->update([
                    'password'=> Hash::make($req->password),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Your password has been changed',
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided password does not match your current password.',
                ],400);
            }
    }

    public function update(Request $req)
    {
        try {
            $auth = JWTAuth::user();
            $user = User::find($auth->id);
            if ($req->hasFile('avatar')) {
                $image = $req->file('avatar');
                $this->media->unlinkProfilePic($user->avatar,'user');
                $name  = $this->media->getFileName($image);
                $path  = $this->media->getProfilePicPath('user');
                $image->move($path, $name);
                $user->avatar = $name;
            }
            else{
                if($req->name){
                    $user->name = $req->name;
                }
                if($req->phone){
                    $user->phone = $req->phone;
                }
                if($req->email){
                    $validate = Request()->validate([
                        'email' => 'required|email',
                    ]);
                    $user->email = $req->email;
                }
                if($req->address){
                    $user->address = $req->address;
                }
                if($req->dob){
                    $user->dob = $req->dob;
                }
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Profile has been updated successfully',
                'user' => $user
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
        
    }
}
