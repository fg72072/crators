<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use JWTAuth;
class Common extends Model
{
    //
    
    // store reviews function for reuse
    public static function reviewStore($req,$id,$rating,$description,$status = '1')
    {
        $req->validate([
            'rating' => 'required|min:1|max:5|regex:/^(-)?[1-5]+(\.[5-5]{1,1})?$/',
            'description' => 'required',
        ]);
        try {
            $review = new Review;
            $review->user_id = JWTAuth::user()->id;
            $review->review_against = 1;
            $review->rating = $rating;
            $review->description = $description;
            if($req->type == 'product' || !$req->type){
                $review->type = '0';
            }
            else if($req->type == 'service'){
                $review->type = '1';
            }
            else if($req->type == 'user'){
                $review->type = '2';
            }
            $review->status = $status;
            if($review->save()){
                return response()->json([
                    'success' => true,
                    'message'=>'Review Successfully saved.',
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e], 400);
        }
    }
}
