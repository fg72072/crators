<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Events\UserCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\ImageHash\ImageHash;
use Illuminate\Support\Facades\Cache;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $user  = Product::with('user')->get();

        // $user = Cache::remember('h',43,function(){
        //     return DB::table('products')->join('users','products.user_id','users.id')->get();
        // });

        $hasher = new ImageHash(new DifferenceHash());
        $hash1 = $hasher->hash(public_path().'/assets/images/cnic.jpeg');
        $hash2 = $hasher->hash(public_path().'/assets/images/cnic.jpeg');

        $distance = $hash1->distance($hash2);
        echo $distance;
        // return view('welcome',compact('user'));
    }
}
