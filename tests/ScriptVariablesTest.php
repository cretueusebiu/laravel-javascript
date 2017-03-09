<?php

namespace Eusebiu\JavaScript\Test;

use Eusebiu\JavaScript\ScriptVariables;

class ScriptVariablesTest extends \PHPUnit_Framework_TestCase
{
    public function testAddKeyValueVariable()
    {
        $sv = (new ScriptVariables())->add('foo', 'bar');

        $this->assertEquals('<script>window.config = {"foo":"bar"};</script>', $sv->render()->toHtml());
    }

    public function testAddArrayVariable()
    {
        $sv = (new ScriptVariables())->add([
            'key1' => 'foo',
            'key2' => 'bar',
        ]);

        $this->assertEquals('<script>window.config = {"key1":"foo","key2":"bar"};</script>', $sv->render()->toHtml());
    }

    public function testAddNestedArrayVariable()
    {
        $sv = (new ScriptVariables())->add([
            'data.user' => 'foo',
        ]);

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', $sv->render()->toHtml());
    }

    public function testAddVariableViaClosure()
    {
        $sv = (new ScriptVariables())->add(function () {
            return [
                'data.user' => 'foo',
            ];
        });

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', $sv->render()->toHtml());
    }

    public function testSetNamespace()
    {
        $this->assertEquals('<script>window.custom = [];</script>', (new ScriptVariables())->render('custom')->toHtml());
    }
}
