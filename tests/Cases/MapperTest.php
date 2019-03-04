<?php

namespace Enphpity\Pdorm\Test\Cases;

use Enphpity\Pdorm\Test\BaseCase;
use Enphpity\Pdorm\Manager;
use Enphpity\Pdorm\Test\Entity\Plan;
use Enphpity\Pdorm\Test\Entity\PlanHydratorProxy;
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

    public function testWhere()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $collection = $mapper->where(['id' => 1]);

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testOr()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $collection = $mapper->or(['id' => 1, 'name' => 'intro']);

        $this->assertEquals('free', $collection[1]->name);
        $this->assertEquals('intro', $collection[2]->name);
    }

    public function testAll()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $collection = $mapper->all();

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testHydrator()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $hydrator = new PlanHydratorProxy();

        $mapper = new PlanMapper(Plan::class, null, $hydrator);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $collection = $mapper->all();

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testStore()
    {
        $insstnace = new Manager();

        $pdo = $this->pdo;

        $insstnace->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $insstnace->addMapper(Plan::class, $mapper);

        $mapper = $insstnace->getMapper(Plan::class);

        $plan = new Plan();

        $plan->name = 'silver';

        $plan = $mapper->store($plan);

        $this->assertEquals('3', $plan->getId());

        $plan->name = 'gold';

        $mapper->store($plan);

        $plan = null;

        $plan = $mapper->find(3);

        $this->assertEquals('gold', $plan->name);

        $plan->name = 'silver';

        $mapper->store([$plan]);

        $plan = null;

        $plan = $mapper->find(3);

        $this->assertEquals('silver', $plan->name);
    }
}
