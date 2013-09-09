<?php

include __DIR__ . "/../vendor/autoload.php";

use G\IDisposable;

class Bar implements IDisposable
{
    public function hello($name)
    {
        return "Hello {$name}";
    }

    public function dispose()
    {
        echo "Dispose Bar";
    }
}

class Foo implements IDisposable
{
    public function hello($name)
    {
        return "Hello {$name}";
    }

    public function dispose()
    {
        echo "Dispose Foo";
    }
}

using([new Bar, new Foo], function (Bar $bar, Foo $foo) {
        echo $bar->hello("Gonzalo");
        echo $foo->hello("Gonzalo");
    });

