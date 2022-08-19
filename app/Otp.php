<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    // 0 = login
    // 1 = forgot

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
