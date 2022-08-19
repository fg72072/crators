<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Media;
use Exception;
use App\Product;
use Illuminate\Http\Request;
use App\Container\CommonContainer;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    protected $media;

    public function __construct(CommonContainer $media){
        return $this->media = $media;

    }
    
    public function store($id,Request $req)
    {
        $validate = Request()->validate([
            'image' => 'required',
        ]);
        try{
            $product = Product::where('user_id',JWTAuth::user()->id)->where('id',$id)->first();
            if($product){
                if ($req->hasFile('image')) {
                for ($i = 0; $i < count($req->file('image')); $i++) {
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
                          
                        }
                    }
                    return response()->json([
                        'success' => true,
                        'message'=>'Media has been add successfully',
                    ],200);
            }
            }
            else{
                return response()->json(['success'=>false,'message'=>'product not found'],404);
            }
            }
            catch(Exception $e){
                return response()->json(['success'=>false,'message'=>'something goes wrong'],400);
        }
    }

    public function destroy($id)
    {
        try{
            $media = Media::where('user_id',JWTAuth::user()->id)->where('id',$id)->first();
            if($media){
                $this->media->unlinkProfilePic($media->file,'product');
                $media->delete();
                return response()->json([
                    'success' => true,
                    'message'=>'Image has been deleted successfully',
                ],200);
            }
            else{
            return response()->json(['success'=>false,'message'=>'image not found'],404);

            }
        }
        catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something goes wrong'],400);
        }
    }

}
