implementation of C# "using" statement in PHP

[![Build Status](https://travis-ci.org/gonzalo123/using.png?branch=master)](https://travis-ci.org/gonzalo123/using)
[![Latest Stable Version](https://poser.pugx.org/gonzalo123/using/v/stable.png)](https://packagist.org/packages/gonzalo123/using)

## Usage
```php
using(new File(__DIR__ . "/file.txt", 'w'), function (File $file) {
        $file->write("Hello\n");
        $file->write("Hello\n");
        $file->write("Hello\n");
    });
```

## The problem

Imagine this class:
```php
class File
{
    private $resource;

    public function __construct($filename, $mode)
    {
        $this->resource = fopen($filename, $mode);
    }

    public function write($string)
    {
        fwrite($this->resource, $string);
    }

    public function close()
    {
        fclose($this->resource);
    }
}
```

We can use this class:
```php
$file = new File(__DIR__ . "/file.txt", 'w');
$file->write("Hello\n");
// ...
// some other things
// ...
$file->write("Hello\n");
$file->close();
```

What happens if there is an exceptions in "some other things"? Simple: close() function isn't called.

## The solution
We can solve the problem with try - catch:

```php
try {
    $file->write("Hello\n");
    // ...
    // some other things
    // ...
    $file->write("Hello\n");
    $file->close();
} catch (\Exception $e) {
    $file->close();
}
```

or using "finally" keyword is we use PHP5.5

```php
try {
    $file->write("Hello\n");
    // ...
    // some other things
    // ...
    $file->write("Hello\n");
} catch (\Exception $e) {
} finally {
    $file->close();
}
```

# Better solution

c# has "using" statement to solve this problem in a smart way.

http://msdn.microsoft.com/en-us//library/yh598w02(v=vs.90).aspx

We're going to implement something similar in PHP.

First we will add G\IDisposable interface to our File class
```php
namespace G;

interface IDisposable
{
    public function dispose();
}
```

Now our File class looks like this:

```php
class File implements IDisposable
{
    private $resource;

    public function __construct($filename, $mode)
    {
        $this->resource = fopen($filename, $mode);
    }

    public function write($string)
    {
        fwrite($this->resource, $string);
    }

    public function close()
    {
        fclose($this->resource);
    }

    public function dispose()
    {
        $this->close();
    }
}
```

And we can use our "using" funcion in PHP:
```php
using(new File(__DIR__ . "/file.txt", 'w'), function (File $file) {
        $file->write("Hello\n");
        $file->write("Hello\n");
        $file->write("Hello\n");
    });
```

As we can see we can forget to close() our file instance. "using" will do it for us, even if one exception is triggered inside.

We also can use an array of instances (implementing the IDisposable interface of course)

```php
using([new Bar, new Foo], function (Bar $bar, Foo $foo) {
        echo $bar->hello("Gonzalo");
        echo $foo->hello("Gonzalo");
    });
```