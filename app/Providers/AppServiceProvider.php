<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {

            if (Auth::check()) {

                $user = Auth::user();

                $notifications = $user->unreadNotifications()
                    ->latest()
                    ->take(10)
                    ->get();

                $unreadCount = $user->unreadNotifications()->count();
            } else {

                $notifications = collect();
                $unreadCount = 0;
            }

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        });
    }
}
