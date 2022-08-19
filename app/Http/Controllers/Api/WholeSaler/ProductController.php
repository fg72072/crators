<?php

namespace App\Http\Controllers\Api\WholeSaler;

use JWTAuth;
use App\Media;
use Exception;
use Illuminate\Http\Request;
use App\Container\CommonContainer;
use App\Http\Controllers\Controller;
use App\Product;

class ProductController extends Controller
{

    protected $media;

    public function __construct(CommonContainer $media){
        return $this->media = $media;

    }
    public function index(Request $req)
    {
        try{
            $product = Product::with('category','brand','medias')
            ->where('user_id',JWTAuth::user()->id)
            ->where('title', 'like', '%' . $req->title . '%');
            if($req->paginate == 'all'){
                $product =   $product->get();
            }
            else{
                $product =   $product->paginate($req->paginate);
            }
            $product->appends(['title'=>$req->title,'paginate'=>$req->paginate]);
            return response()->json([
                'success' => true,
                'data'=>$product,
            ],200);
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>$e],400);
        }
    }
    public function store(Request $req)
    {
        $validate = Request()->validate([
            'title' => 'required',
            'price' => 'required|regex:/^(-)?[0-9]+(\.[0-9]{1,2})?$/',
            'category' => 'required',
            'brand' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);
        try{
            $product = new Product;
            $product->user_id = JWTAuth::user()->id;
            $product->title = $req->title;
            $product->price = $req->price;
            $product->cat_id = $req->category;
            $product->brand_id = $req->brand;
            $product->description = $req->description;
            if($product->save()){
            for ($i = 0; $i < count($req->file('image')); $i++) {
            # code...
            if ($req->hasFile('image')) {
                $image = $req->file('image')[$i];
                $name  = $this->media->getFileName($image);
                $path  = $this->media->getProfilePicPath('product');
                $image->move($path, $name);
                $uploadmedia = new Media();
                $uploadmedia->user_id = JWTAuth::user()->id;
                $uploadmedia->file = $name;
                $uploadmedia->type = '1';
                $uploadmedia->media_against = $product->id;
                if ($uploadmedia->save()) {
                    return response()->json([
                        'success' => true,
                        'message'=>'Product has been add successfully',
                    ],200);
                }
            }
             }
            }
            }
            catch(Exception $e){
                return response()->json(['success'=>false,'message'=>'something goes wrong'],400);
        }
    }

    public function edit($id)
    {
        try{
            $product = Product::with('category','brand','medias')->where('user_id',JWTAuth::user()->id)->where('id',$id)->first();
            return response()->json([
                'success' => true,
                'data'=>$product,
            ],200);
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong'],400);
        }
    }
    
    public function update($id,Request $req)
    {
        $validate = Request()->validate([
            'title' => 'required',
            'price' => 'required|regex:/^(-)?[0-9]+(\.[0-9]{1,2})?$/',
            'category' => 'required',
            'brand' => 'required',
            'description' => 'required',
        ]);
        try{
            $product = Product::where('user_id',JWTAuth::user()->id)->where('id',$id)->first();
            $product->title = $req->title;
            $product->price = $req->price;
            $product->cat_id = $req->category;
            $product->brand_id = $req->brand;
            $product->description = $req->description;
            if($product->save()){
                return response()->json([
                    'success' => true,
                    'message'=>'Product has been update successfully',
                ],200);
            }
            }
            catch(Exception $e){
                return response()->json(['success'=>false,'message'=>'something goes wrong'],400);
        }
    }

}
