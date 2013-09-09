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
