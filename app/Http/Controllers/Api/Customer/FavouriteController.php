<?php

namespace App\Http\Controllers\Api\Customer;

use JWTAuth;
use Exception;
use App\Product;
use App\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function index(Request $req)
    {
        $favourites = Favourite::with('product.medias','product.user:id,name','user')->where('user_id',JWTAuth::user()->id)->where('type','0')->get();
        return response()->json([
            'success' => true,
            'favourites' => $favourites,
        ], 200);
    }

    public function addOrRemoveFavourite(Request $req,$id)
    {
        try {
            $user = JWTAuth::user();
            $favourite = Favourite::where('user_id',$user->id)->where('against_id',$id)->first();
            if($favourite){
                $favourite->delete();
                return response()->json([
                    'success' => true,
                    'message'=>'Remove Successfully To Favourite',
                ],200);
            }
            else{
                $fav = new Favourite;
                $fav->user_id = $user->id;
                $fav->against_id = $id;
                if($fav->save()){
                    return response()->json([
                        'success' => true,
                        'message'=>'Add Successfully To Favourite',
                    ],200);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 'message' => 'something goes wrong'
            ], 400);
        }
    }

}
