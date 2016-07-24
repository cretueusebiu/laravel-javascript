<?php

namespace Eusebiu\LaravelJavaScript;

use stdClass;
use Exception;
use JsonSerializable;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class JavaScriptTransformer
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $types = [
        'String',
        'Array',
        'Object',
        'Numeric',
        'Boolean',
        'Null'
    ];

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * Create a new JS transformer instance.
     *
     * @param  string $namespace
     * @return void
     */
    public function __construct($namespace = 'config')
    {
        $this->namespace = $namespace;
    }

    /**
     * Set a given variable.
     *
     * @param  array|string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set($this->variables, $innerKey, $innerValue);
            }
        } else {
            Arr::set($this->variables, $key, $value);
        }
    }

    /**
     * Transform the PHP array of variables to the JavaScript object.
     *
     * @return string
     */
    public function build()
    {
        $js = "window.{$this->namespace} = {";

        foreach ($this->variables as $key => $value) {
            $js .= $this->buildKeyValuePair($key, $value).',';
        }

        $js .= '};';

        return new HtmlString("<script>{$js}</script>");
    }

    /**
     * Buld the key-value pair.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    protected function buildKeyValuePair($key, $value)
    {
        return "{$key}:{$this->optimizeValueForJavaScript($value)}";
    }

    /**
     * Format a value for JavaScript.
     *
     * @param  string $value
     * @return string
     *
     * @throws \Exception
     */
    protected function optimizeValueForJavaScript($value)
    {
        foreach ($this->types as $transformer) {
            $js = $this->{"transform{$transformer}"}($value);

            if (! is_null($js)) {
                return $js;
            }
        }
    }

    /**
     * Transform a string.
     *
     * @param  string $value
     * @return string
     */
    protected function transformString($value)
    {
        if (is_string($value)) {
            return "'{$this->escape($value)}'";
        }
    }

    /**
     * Transform an array.
     *
     * @param  array $value
     * @return string
     */
    protected function transformArray($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
    }

    /**
     * Transform a numeric value.
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function transformNumeric($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
    }

    /**
     * Transform a boolean.
     *
     * @param  boolean $value
     * @return string
     */
    protected function transformBoolean($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
    }

    /**
     * Transform an object.
     *
     * @param  object $value
     * @return string
     * @throws Exception
     */
    protected function transformObject($value)
    {
        if (! is_object($value)) {
            return;
        }

        if ($value instanceof JsonSerializable || $value instanceof StdClass) {
            return json_encode($value);
        }

        if (method_exists($value, 'toJson')) {
            return $value;
        }

        if (! method_exists($value, '__toString')) {
            throw new Exception('Cannot transform this object to JavaScript.');
        }

        return "'{$value}'";
    }

    /**
     * Transform "null."
     *
     * @param  mixed $value
     * @return string
     */
    protected function transformNull($value)
    {
        if (is_null($value)) {
            return 'null';
        }
    }

    /**
     * Escape any single quotes.
     *
     * @param  string $value
     * @return string
     */
    protected function escape($value)
    {
        return str_replace(["\\", "'"], ["\\\\", "\'"], $value);
    }
}
