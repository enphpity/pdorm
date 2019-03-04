<?php

namespace Enphpity\Pdorm\Test\Cases\System;

use Enphpity\Pdorm\System\Connection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ConnectionTest extends TestCase
{
    public function testConstructor()
    {
        $config = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $conn);

        $conn->connect();
    }

    public function testConnect()
    {
        $config = [
            'driver' => 'mysql',
            'user' => 'test',
            'password' => 'password',
            'host' => 'localhost',
            'dbname' => 'testdb',
        ];

        $conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $conn);
        $this->expectException(RunTimeException::class);
        $conn->connect();

        $conn = null;

        $config2 = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $conn2 = new Connection($config2);

        $this->assertInstanceOf(Connection::class, $conn2);

        $conn2->connect();
        $conn2->disconnect();
    }
}
