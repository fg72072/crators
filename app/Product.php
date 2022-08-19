<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JWTAuth;
class Product extends Model
{
    use SoftDeletes;
    
    protected $appends = ['rating'];

    function category()
    {
        return $this->belongsTo(Category::class, 'cat_id', 'id');
    }

    function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function medias()
    {
        return $this->hasMany(Media::class, 'media_against', 'id')->where('type',1);
    }

    function favourite()
    {
        return $this->hasOne(Favourite::class, 'against_id', 'id')->where('user_id',JWTAuth::user()->id)->where('type','0');
    }

    function reviews()
    {
        return $this->hasMany(Review::class, 'review_against', 'id')->where('type',0);
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ? : 0;
    }
}
