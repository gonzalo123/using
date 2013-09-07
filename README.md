implementation of C# "using" statement in PHP

[![Build Status](https://travis-ci.org/gonzalo123/using.png?branch=master)](https://travis-ci.org/gonzalo123/using)

http://msdn.microsoft.com/en-us//library/yh598w02(v=vs.90).aspx

```php

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
```