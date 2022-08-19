<?php

namespace App\Http\Controllers\Api\Customer;

use JWTAuth;
use App\Order;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function index(Request $req)
    {
        try {
            $orders = Order::where('user_id',JWTAuth::user()->id)
            ->withCount('orderItems')
            ->with('user:id,avatar,name','country:id,name','city:id,name')
            ->get();
            return response()->json([
                'success' => true,
                'orders'=>$orders,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

    public function show(Request $req,$id)
    {
        try {
            $order = Order::where('id',$id)->where('user_id',JWTAuth::user()->id)
            ->with('user:id,avatar,name','orderItems.product.medias','orderItems.product.user:id,name','country:id,name','city:id,name')
            ->first();
            return response()->json([
                'success' => true,
                'order'=>$order,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

}
