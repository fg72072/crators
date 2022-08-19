<?php

namespace App\Http\Controllers\Api\Customer;

use JWTAuth;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ProductController extends Controller
{

    public function index(Request $req)
    {
        try {
            $products = Product::with('user:id,name,avatar','medias','brand','category')
            ->where('title', 'like', '%' . $req->title . '%')->get();
            return response()->json([
                'success' => true,
                'products'=>$products,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

    public function show(Request $req,$id)
    {
        try {
            $product = Product::with('user:id,name,avatar','medias','brand','category','favourite','reviews.user:id,avatar,name')->where('id',$id)->first();
            return response()->json([
                'success' => true,
                'product'=>$product,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

}
