<?php

namespace Enphpity\Pdorm;

use Enphpity\Pdorm\System\Connection;
use PDO;

class Manager
{
    protected static $instance = null;

    protected $connections = [];
    
    protected $mappers = [];

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct($config = false)
    {
        if ($config) {
            $connection = new Connection($config);
            $this->connections[Connection::DEFAULT] = $connection;
        }

        self::$instance = $this;
    }

    public function addConnection($connection, $name = Connection::DEFAULT)
    {
        if (is_array($connection) || $connection instanceof PDO) {
            $connection = new Connection($connection);
        }

        if ($connection instanceof Connection) {
            $this->connections[$name] = $connection;
        }

        return $this;
    }

    public function getConnection($name = Connection::DEFAULT)
    {
        return isset($this->connections[$name]) ? $this->connections[$name] : null;
    }

    public function addMapper($entityName, $mapper)
    {
        $this->mappers[$entityName] = $mapper;

        return $this;
    }

    public function getMapper($entityName)
    {
        return $this->mappers[$entityName];
    }
}
