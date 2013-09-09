<?php

include __DIR__ . "/../vendor/autoload.php";

use G\IDisposable;

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

using(new File(__DIR__ . "/file.txt", 'w'), function (File $file) {
        $file->write("Hello\n");
        $file->write("Hello\n");
        $file->write("Hello\n");
    });


$file = new File(__DIR__ . "/file.txt", 'w');
$file->write("Hello\n");
// ...
// some other things
// ...
$file->write("Hello\n");
$file->close();


$file = new File(__DIR__ . "/file.txt", 'w');
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

/*
$file = new File(__DIR__ . "/file.txt", 'w');
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
*/

/*
 * It would be cool, isn't it?

using(new File(__DIR__ . "/file.txt", 'w') {
        $file->write("Hello\n");
        $file->write("Hello\n");
        $file->write("Hello\n");
    });
*/