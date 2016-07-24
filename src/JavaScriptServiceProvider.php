<?php

namespace Eusebiu\LaravelJavaScript;

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
            $namespace = 'config';

            return new JavaScriptTransformer($namespace);
        });

        $this->app->alias('javascript', JavaScriptTransformerInterface::class);
    }
}
