<?php

include __DIR__ . '/src/Foo.php';
include __DIR__ . '/src/Bar.php';

class UsingTest extends \PHPUnit_Framework_TestCase
{
    function test_simple_usage()
    {
        $disponseCalled = false;

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())
            ->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled = true;
                    }));

        $this->assertFalse($disponseCalled, 'disponse not called');

        using($foo, function (Foo $foo) {
                $foo->hello("Gonzalo");
            });

        $this->assertTrue($disponseCalled, 'disponse has been called');
    }

    function test_exception()
    {
        $disponseCalled = false;

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled = true;
                    }));

        $foo->expects($this->any())->method('hello')
            ->will($this->returnCallback(function() {
                        throw new \Exception('hello function exception');
                    }));

        $this->assertFalse($disponseCalled, 'disponse not called');

        try {
            using($foo, function (Foo $foo) {
                    $foo->hello("Gonzalo");
                });
        } catch (\Exception $e) {}

        $this->assertTrue($disponseCalled, 'disponse has been called');
    }

    function test_using_two_disponsables()
    {
        $disponseCalled = [
            'foo' => false,
            'bar' => false,
        ];

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled['foo'] = true;
                    }));

        $bar = $this->getMockBuilder('Bar')->getMock();
        $bar->expects($this->any())->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled['bar'] = true;
                    }));

        $this->assertFalse($disponseCalled['foo'], 'Foo disponse has been called');
        $this->assertFalse($disponseCalled['bar'], 'Bar disponse has been called');

        using([$foo, $bar], function (Foo $foo, Bar $bar) {
                $foo->hello("Gonzalo");
                $bar->hello("Gonzalo");
            });

        $this->assertTrue($disponseCalled['foo'], 'Foo disponse has been called');
        $this->assertTrue($disponseCalled['bar'], 'Bar disponse has been called');
    }

    function test_using_two_disponsables_with_exceptions_calling_both_disponses()
    {
        $disponseCalled = [
            'foo' => false,
            'bar' => false,
        ];

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled['foo'] = true;
                    }));

        $foo->expects($this->any())->method('hello')->will($this->returnCallback(function() {
                    throw new \Exception('hello function exception');
                }));

        $bar = $this->getMockBuilder('Bar')->getMock();
        $bar->expects($this->any())->method('disponse')
            ->will($this->returnCallback(function() use (&$disponseCalled) {
                        $disponseCalled['bar'] = true;
                    }));

        $this->assertFalse($disponseCalled['foo'], 'Foo disponse has been called');
        $this->assertFalse($disponseCalled['bar'], 'Bar disponse has been called');

        try {
            using([$foo, $bar], function (Foo $foo, Bar $bar) {
                    $foo->hello("Gonzalo");
                    $bar->hello("Gonzalo");
                });
        } catch (\Exception $e) {}

        $this->assertTrue($disponseCalled['foo'], 'Foo disponse has been called');
        $this->assertTrue($disponseCalled['bar'], 'Bar disponse has been called');
    }
}