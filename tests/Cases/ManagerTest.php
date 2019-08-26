<?php

namespace Enphpity\Pdorm\Test\Cases;

use Enphpity\Pdorm\Test\BaseCase;
use Enphpity\Pdorm\Manager;
use Enphpity\Pdorm\Test\Entity\Plan;
use Enphpity\Pdorm\Test\Mapper\PlanMapper;
use Enphpity\Pdorm\System\Connection;

class ManagerTest extends BaseCase
{
    public function testGetInsstance()
    {
        $instance = Manager::getInstance();

        $this->assertInstanceOf(Manager::class, $instance);
    }

    public function testConstructor()
    {
        $instance = new Manager();

        $this->assertInstanceOf(Manager::class, $instance);

        $instance = new Manager($this->pdo);

        $this->assertInstanceOf(Manager::class, $instance);
    }

    public function testAddConnection()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $connection = $instance->getConnection();

        $this->assertInstanceOf(Connection::class, $connection);
    }

    public function testAddMapper()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }

    public function testGetdMapper()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }
}
