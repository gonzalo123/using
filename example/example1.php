<?php

include __DIR__ . "/../vendor/autoload.php";

use G\DisposableInterface;

class Bar implements DisposableInterface
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

class Foo implements DisposableInterface
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

using(new Bar, new Foo, function (Bar $bar, Foo $foo) {
        echo $bar->hello("Gonzalo");
        echo $foo->hello("Gonzalo");
    });

