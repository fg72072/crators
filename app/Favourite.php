<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    
    function product()
    {
        return $this->belongsTo(Product::class, 'against_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
