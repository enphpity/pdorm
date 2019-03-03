<?php

namespace Enphpity\Pdorm\Test\Cases;

use Enphpity\Pdorm\Test\BaseCase;
use Enphpity\Pdorm\Manager;
use Enphpity\Pdorm\Test\Entity\Plan;
use Enphpity\Pdorm\Test\Mapper\PlanMapper;

class MapperTest extends BaseCase
{
    public function testConstructor()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }

    public function testFind()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $plan = $mapper->find(1);

        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertEquals('free', $plan->name);
    }
}
