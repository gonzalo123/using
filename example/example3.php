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

class Foo
{
    public function bar()
    {
        using(new Bar, function(Bar $bar) {
                $bar->hello("Gonzalo");
            });
    }
}


$foo = new Foo();
echo $foo->bar();
