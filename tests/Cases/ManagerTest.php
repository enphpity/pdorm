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
        $insstnace = Manager::getInstance();

        $this->assertInstanceOf(Manager::class, $insstnace);
    }

    public function testConstruct()
    {
        $insstnace = new Manager();

        $this->assertInstanceOf(Manager::class, $insstnace);

        $insstnace = new Manager($this->pdo);

        $this->assertInstanceOf(Manager::class, $insstnace);
    }

    public function testAddConnection()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);
        
        $connection = $insstnace->getConnection();

        $this->assertInstanceOf(Connection::class, $connection);
    }

    public function testAddMapper()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }

    public function testGetdMapper()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }
}
