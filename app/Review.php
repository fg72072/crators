<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    // type >>>>>>
    // 0 = product
    // 1 = service
    // 2 = user 
    
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
