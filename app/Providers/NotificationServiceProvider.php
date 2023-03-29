<?php

namespace App\Providers;

use View;
use Auth;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view){
            $notificationCount = 0;
            if (Auth::check()) {
                $notificationCount = auth()->user()->unreadNotifications->count();
            }
            $view->with('noticount', $notificationCount);
        });
    }
}
