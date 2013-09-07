<?php

include __DIR__ . "/../vendor/autoload.php";

use G\IDisponsable;

class Bar implements IDisponsable
{
    public function hello($name)
    {
        return "Hello {$name}";
    }

    public function disponse()
    {
        echo "Disponse Bar";
    }
}

class Foo implements IDisponsable
{
    public function hello($name)
    {
        return "Hello {$name}";
    }

    public function disponse()
    {
        echo "Disponse Foo";
    }
}

using([new Bar, new Foo], function (Bar $bar, Foo $foo) {
        echo $bar->hello("Gonzalo");
        echo $foo->hello("Gonzalo");
    });

