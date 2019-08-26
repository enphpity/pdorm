<?php

namespace Enphpity\Pdorm\Test\Cases\System;

use Enphpity\Pdorm\System\Connection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ConnectionTest extends TestCase
{
    protected $conn;

    public function testConstructor()
    {
        $config = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $this->conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $this->conn);

        $this->conn->connect();
    }

    public function testConnectOnIncorrectConfig()
    {
        $config = [
            'driver' => 'mysql',
            'user' => 'test',
            'password' => 'password',
            'host' => 'localhost',
            'dbname' => 'testdb',
        ];

        $this->conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $this->conn);
        $this->expectException(RunTimeException::class);
        $this->conn->connect();
    }

    public function testConnectOnCorrectConfig()
    {
        $config = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $this->conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $this->conn);

        $this->conn->connect();
        $this->conn->disconnect();
    }

    public function testConnectOnDuplicatedConnection()
    {
        $config = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $this->conn = new Connection($config);

        $this->assertInstanceOf(Connection::class, $this->conn);

        $this->conn->connect();

        $this->assertNull($this->conn->connect());

        $this->conn->disconnect();
    }

    public function testDisconnect()
    {
        $config = [
            'driver' => 'sqlite',
            'path' => 'memory',
        ];

        $this->conn = new Connection($config);

        $this->conn->connect();
        $this->conn->disconnect();

        $this->assertInstanceOf(Connection::class, $this->conn->connect());

        $this->conn->disconnect();
    }

    protected function tearDown(): void
    {
        $this->conn = null;
    }
}
