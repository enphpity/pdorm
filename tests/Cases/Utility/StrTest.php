<?php

namespace Enphpity\Pdorm\Test\Cases\Utility;

use Enphpity\Pdorm\Utility\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testKebab()
    {
        $this->assertEquals('laravel-php-framework', Str::kebab('LaravelPhpFramework'));
        $this->assertEquals('laravel-php-framework', Str::kebab('LaravelPhpFramework'));
    }

    public function testSnake()
    {
        $this->assertEquals('laravel_p_h_p_framework', Str::snake('LaravelPHPFramework'));
        $this->assertEquals('laravel_php_framework', Str::snake('LaravelPhpFramework'));
        $this->assertEquals('laravel php framework', Str::snake('LaravelPhpFramework', ' '));
        $this->assertEquals('laravel_php_framework', Str::snake('Laravel Php Framework'));
        $this->assertEquals('laravel_php_framework', Str::snake('Laravel    Php      Framework   '));
        // ensure cache keys don't overlap
        $this->assertEquals('laravel__php__framework', Str::snake('LaravelPhpFramework', '__'));
        $this->assertEquals('laravel_php_framework_', Str::snake('LaravelPhpFramework_', '_'));
        $this->assertEquals('laravel_php_framework', Str::snake('laravel php Framework'));
        $this->assertEquals('laravel_php_frame_work', Str::snake('laravel php FrameWork'));
    }

    public function testStudly()
    {
        $this->assertEquals('LaravelPHPFramework', Str::studly('laravel_p_h_p_framework'));
        $this->assertEquals('LaravelPhpFramework', Str::studly('laravel_php_framework'));
        $this->assertEquals('LaravelPhPFramework', Str::studly('laravel-phP-framework'));
        $this->assertEquals('LaravelPhpFramework', Str::studly('laravel  -_-  php   -_-   framework   '));

        $this->assertEquals('FooBar', Str::studly('fooBar'));
        $this->assertEquals('FooBar', Str::studly('foo_bar'));
        $this->assertEquals('FooBar', Str::studly('foo_bar')); // test cache
        $this->assertEquals('FooBarBaz', Str::studly('foo-barBaz'));
        $this->assertEquals('FooBarBaz', Str::studly('foo-bar_baz'));
    }

    public function testCamel()
    {
        $this->assertEquals('laravelPHPFramework', Str::camel('Laravel_p_h_p_framework'));
        $this->assertEquals('laravelPhpFramework', Str::camel('Laravel_php_framework'));
        $this->assertEquals('laravelPhPFramework', Str::camel('Laravel-phP-framework'));
        $this->assertEquals('laravelPhpFramework', Str::camel('Laravel  -_-  php   -_-   framework   '));

        $this->assertEquals('fooBar', Str::camel('FooBar'));
        $this->assertEquals('fooBar', Str::camel('foo_bar'));
        $this->assertEquals('fooBar', Str::camel('foo_bar')); // test cache
        $this->assertEquals('fooBarBaz', Str::camel('Foo-barBaz'));
        $this->assertEquals('fooBarBaz', Str::camel('foo-bar_baz'));
    }
}
