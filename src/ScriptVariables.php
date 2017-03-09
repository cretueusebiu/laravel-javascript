<?php

namespace Eusebiu\JavaScript;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class ScriptVariables
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * Add a variable.
     *
     * @param array|string|\Closure $key
     * @param mixed                 $value
     *
     * @return $this
     */
    public function add($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set($this->variables, $innerKey, $innerValue);
            }
        } else {
            Arr::set($this->variables, $key, $value);
        }

        return $this;
    }

    /**
     * Render as a HTML string.
     *
     * @param string $namespace
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function render($namespace = 'config')
    {
        return new HtmlString(
            '<script>window.'.$namespace.' = '.json_encode($this->variables).';</script>'
        );
    }
}
