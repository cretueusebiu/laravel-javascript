<?php

namespace Eusebiu\LaravelJavaScript;

interface JavaScriptTransformerInterface
{
    /**
     * Set a given variable.
     *
     * @param  array|string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value = null);

    /**
     * Transform the PHP array of variables to the JavaScript object.
     *
     * @return string
     */
    public function build();
}
