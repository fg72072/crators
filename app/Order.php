<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }


    function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
