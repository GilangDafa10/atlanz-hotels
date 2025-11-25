<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Notifications\SendOTPNotification;

class SendOTPEventListener
{
    public function handle(Registered $event)
    {
        // Pastikan relasi activeOTP benar
        $event->user->notify(
            new SendOTPNotification($event->user->activeOTP->otp_code)
        );
    }
}
