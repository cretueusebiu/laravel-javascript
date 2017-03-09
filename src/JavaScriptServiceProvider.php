<?php

namespace Eusebiu\JavaScript;

use Illuminate\Support\ServiceProvider;

class JavaScriptServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('javascript', function ($app) {
            return new ScriptVariables();
        });

        $this->app->alias('javascript', ScriptVariables::class);
    }
}
