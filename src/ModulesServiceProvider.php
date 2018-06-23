<?php

namespace Gcambon\Modules;

use App\Providers\EventServiceProvider;

class ModulesServiceProvider extends EventServiceProvider
{
    public function boot()
    {
        $this->publish();
    }

    private function publish(){
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

}