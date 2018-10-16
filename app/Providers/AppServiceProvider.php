<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');
            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
        $this->app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);

        //
    }
    public function boot()
    {
      Resource::withoutWrapping();
      setlocale(LC_TIME, 'ru_RU.UTF-8');
      Carbon::setLocale(config('app.locale'));
     }
}
