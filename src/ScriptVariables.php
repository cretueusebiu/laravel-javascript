<?php

namespace Eusebiu\JavaScript;

use Closure;
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
        } elseif ($key instanceof Closure) {
            $this->variables[] = $key;
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
        foreach ($this->variables as $key => $variable) {
            if ($variable instanceof Closure) {
                $variable = $variable();

                if (is_array($variable)) {
                    $this->add($variable);
                }
            }
        }

        $variables = array_filter($this->variables, function ($variable) {
            return !$variable instanceof Closure;
        });

        return new HtmlString(
            '<script>window.'.$namespace.' = '.json_encode($variables).';</script>'
        );
    }
}
