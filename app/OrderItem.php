<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //

    function product()
    {
        return $this->belongsTo(Product::class, 'p_id', 'id');
    }

    function deliveryStatus()
    {
        return $this->belongsTo(DeliveryStatus::class, 'status', 'id');
    }
}
