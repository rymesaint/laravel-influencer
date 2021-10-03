<?php

namespace App\Providers;

use App\Events\AdminAddedEvent;
use App\Events\OrderCompletedEvent;
use App\Events\ProductUpdatedEvent;
use App\Listeners\NotifiedAdminListener;
use App\Listeners\NotifiedInfluencerListener;
use App\Listeners\NotifyAdminAddedListener;
use App\Listeners\ProductCacheFlush;
use App\Listeners\UpdateRankingsListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCompletedEvent::class => [
            NotifiedAdminListener::class,
            NotifiedInfluencerListener::class,
            UpdateRankingsListener::class,
        ],
        AdminAddedEvent::class => [
            NotifyAdminAddedListener::class,
        ],
        ProductUpdatedEvent::class => [
            ProductCacheFlush::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
