<?php

namespace App\Providers;

use App\Events\SendMail;
use App\Events\PasswordReset;
use App\Listeners\SendWelcomingEmail;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendChangedPasswordConfirmEmail;
use App\Events\CaseStatusUpdateEvent;
use App\Listeners\CaseStatusUpdateListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendMail::class => [
            SendWelcomingEmail::class
        ],
        PasswordReset::class => [
            SendChangedPasswordConfirmEmail::class
        ],
        CaseStatusUpdateEvent::class => [
            CaseStatusUpdateListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
