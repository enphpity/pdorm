<?php

namespace Enphpity\Pdorm\System;

use Enphpity\Pdorm\Contract\Connection\ConnectionInterface;
use PDO;
use PDOException;
use RunTimeException;

class Connection implements ConnectionInterface
{
    const DEFAULT = 'default';

    protected $config;
    
    protected $connection;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        if ($this->connection) {
            return;
        }

        if ($this->config instanceof PDO) {
            $this->connection = $this->config;

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $this;
        }

        list($dsn, $username, $password, $driverOptions) = $this->extractParams();

        try {
            $this->connection = new PDO($dsn, $username, $password, $driverOptions);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }

        return $this;
    }

    public function disconnect()
    {
        $this->connection = null;
    }

    private function extractParams()
    {
        $driver = $this->config['driver'];
        $user = isset($this->config['user']) ? $this->config['user'] : null;
        $password = isset($this->config['password']) ? $this->config['password'] : null;
        $host = isset($this->config['host']) ? $this->config['host'] : null;
        $path = isset($this->config['path']) ? $this->config['path'] : null;
        $db = isset($this->config['dbname']) ? $this->config['dbname'] : null;
        $driverOptions = isset($this->config['driverOptions']) ? $this->config['driverOptions'] : array();

        $dsn = null;

        switch ($driver) {
            case 'sqlite':
                $path = trim($path, ':');
                if (strpos($path, 'memory') !== false) {
                    $path = ":$path:";
                }
                $dsn = 'sqlite:' . $path;
                break;

            case 'mysql':
                $dsn = "mysql:host=${$host};dbname={$db}";
                break;
        }

        return array($dsn, $user, $password, $driverOptions);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->connection, $name], $arguments);
    }
}
