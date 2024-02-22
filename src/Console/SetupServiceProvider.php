<?php

namespace SSM\Console;

use Illuminate\Support\ServiceProvider;
use SSM\Console\Commands\SetupCommand;

class SetupServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            SetupCommand::class,
        ]);
    }
}
