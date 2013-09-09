<?php

include __DIR__ . '/src/Foo.php';
include __DIR__ . '/src/Bar.php';

class UsingTest extends \PHPUnit_Framework_TestCase
{
    function test_simple_usage()
    {
        $disposeCalled = false;

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())
            ->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled = true;
                    }));

        $this->assertFalse($disposeCalled, 'dispose not called');

        using($foo, function (Foo $foo) {
                $foo->hello("Gonzalo");
            });

        $this->assertTrue($disposeCalled, 'dispose has been called');
    }

    function test_exception()
    {
        $disposeCalled = false;

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled = true;
                    }));

        $foo->expects($this->any())->method('hello')
            ->will($this->returnCallback(function() {
                        throw new \Exception('hello function exception');
                    }));

        $this->assertFalse($disposeCalled, 'dispose not called');

        try {
            using($foo, function (Foo $foo) {
                    $foo->hello("Gonzalo");
                });
        } catch (\Exception $e) {}

        $this->assertTrue($disposeCalled, 'dispose has been called');
    }

    function test_using_two_disposables()
    {
        $disposeCalled = [
            'foo' => false,
            'bar' => false,
        ];

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled['foo'] = true;
                    }));

        $bar = $this->getMockBuilder('Bar')->getMock();
        $bar->expects($this->any())->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled['bar'] = true;
                    }));

        $this->assertFalse($disposeCalled['foo'], 'Foo dispose has been called');
        $this->assertFalse($disposeCalled['bar'], 'Bar dispose has been called');

        using([$foo, $bar], function (Foo $foo, Bar $bar) {
                $foo->hello("Gonzalo");
                $bar->hello("Gonzalo");
            });

        $this->assertTrue($disposeCalled['foo'], 'Foo dispose has been called');
        $this->assertTrue($disposeCalled['bar'], 'Bar dispose has been called');
    }

    function test_using_two_disponsables_with_exceptions_calling_both_disposes()
    {
        $disposeCalled = [
            'foo' => false,
            'bar' => false,
        ];

        $foo = $this->getMockBuilder('Foo')->getMock();
        $foo->expects($this->any())->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled['foo'] = true;
                    }));

        $foo->expects($this->any())->method('hello')->will($this->returnCallback(function() {
                    throw new \Exception('hello function exception');
                }));

        $bar = $this->getMockBuilder('Bar')->getMock();
        $bar->expects($this->any())->method('dispose')
            ->will($this->returnCallback(function() use (&$disposeCalled) {
                        $disposeCalled['bar'] = true;
                    }));

        $this->assertFalse($disposeCalled['foo'], 'Foo dispose has been called');
        $this->assertFalse($disposeCalled['bar'], 'Bar dispose has been called');

        try {
            using([$foo, $bar], function (Foo $foo, Bar $bar) {
                    $foo->hello("Gonzalo");
                    $bar->hello("Gonzalo");
                });
        } catch (\Exception $e) {}

        $this->assertTrue($disposeCalled['foo'], 'Foo dispose has been called');
        $this->assertTrue($disposeCalled['bar'], 'Bar dispose has been called');
    }
}