<?php

namespace App\Http\Controllers\Api\WholeSaler;

use JWTAuth;
use App\Media;
use App\Order;
use Exception;
use App\OrderItem;
use Illuminate\Http\Request;
use App\Container\CommonContainer;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    // get all orders 
    public function index(Request $req)
    {
        try{
            $orders = Order::where('id', 'like', '%' . $req->id . '%')->with('user:id,avatar,name','orderItems.deliveryStatus','country:id,name','city:id,name')
            ->whereHas('orderItems.product', function ($query) {
                $query->where('user_id', '=', JWTAuth::user()->id);
            });
            if($req->paginate == 'all'){
                $orders = $orders->get();
            }
            else{
                $orders = $orders->paginate($req->paginate);
            }
            $orders->appends(['title'=>$req->title,'paginate'=>$req->paginate]);

            return response()->json([
                'success' => true,
                'orders'=>$orders,
            ],200);
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong.'],400);
        }
    }
    // get single order 
    public function show($id)
    {
        try{
            $order = Order::where('id',$id)->with('user:id,avatar,name','orderItems.deliveryStatus','country:id,name','city:id,name')
            ->whereHas('orderItems.product', function ($query) {
                $query->where('user_id', '=', JWTAuth::user()->id);
            })->first();
            if($order){
                return response()->json([
                    'success' => true,
                    'order'=>$order,
                ],200);
            }
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong.'],400);
        }
    }
    // get new orders 
    public function newOrder(Request $req)
    {
        try{
            $orders = Order::with('user:id,avatar,name','orderItems.deliveryStatus','country:id,name','city:id,name')
            ->whereHas('orderItems.product', function ($query) {
                $query->where('user_id', '=', JWTAuth::user()->id);
            })->whereHas('orderItems', function ($query) {
                $query->where('is_seen','0');
            })->get();
            return response()->json([
                'success' => true,
                'orders'=>$orders,
            ],200);
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong.'],400);
        }
    }
    // update order status or order seen 
    public function updateOrder(Request $req,$id)
    {
        try{
            if($req->type == 'status' && $req->status){
            $order = OrderItem::where('order_id',$id)->whereHas('product',function($q){
                $q->where('user_id',JWTAuth::user()->id);
            })->update(['status'=>$req->status]);
            }
            else{
            $order = OrderItem::where('order_id',$id)->whereHas('product',function($q){
                $q->where('user_id',JWTAuth::user()->id);
            })->update(['is_seen'=>'1']);
            }
            return response()->json([
                'success' => true,
                'message'=>'order has been updated successfully.',
            ],200);
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong.'],400);
        }
    }

}
