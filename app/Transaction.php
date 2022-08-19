<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use JWTAuth;
class Transaction extends Model
{
    // 0 = order
    // 1 = booking
    
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function transaction($payment_against,$txn_id,$amount,$description = '',$type)
    {
        $trans = new Transaction;
        $trans->user_id = JWTAuth::user()->id;
        $trans->payment_againts = $payment_against;
        $trans->txn_id = $txn_id;
        $trans->amount = $amount;
        $trans->description = $description;
        $trans->type = $type;
        $trans->save();
    }
}
