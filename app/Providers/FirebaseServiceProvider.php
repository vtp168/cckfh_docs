<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseNotificationService;
use Kreait\Firebase\Messaging;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FirebaseNotificationService::class, function($app){
            $messaging = $app->make(Messaging::class);
            return new FirebaseNotificationService($messaging);
        });
    }

    public function boot()
    {
        //
    }
}
