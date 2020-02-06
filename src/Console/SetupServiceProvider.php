<?php

namespace SSM\Console;

use Roots\Acorn\ServiceProvider;
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