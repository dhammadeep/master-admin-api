<?php

namespace App\Listeners;

use App\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangePasswordConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendChangedPasswordConfirmEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        Mail::to($event->data['email'])->send(new ChangePasswordConfirmation($event->data));
    }
}
