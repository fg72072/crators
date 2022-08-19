<?php

namespace App\Listners;

use App\Mail\OTP;
use App\Events\UserCreate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreate  $event
     * @return void
     */
    public function handle(UserCreate $event)
    {
        Mail::to($event->email)->send(new OTP('2333'));
    }
}
