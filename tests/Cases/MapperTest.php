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
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $this->assertInstanceOf(PlanMapper::class, $mapper);
    }

    public function testFind()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $plan = $mapper->find(1);

        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertEquals('free', $plan->name);
    }

    public function testWhere()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $collection = $mapper->where(['id' => 1]);

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testOr()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $collection = $mapper->or(['id' => 1, 'name' => 'intro']);

        $this->assertEquals('free', $collection[1]->name);
        $this->assertEquals('intro', $collection[2]->name);
    }

    public function testAll()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $collection = $mapper->all();

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testHydrator()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $hydrator = new PlanHydratorProxy();

        $mapper = new PlanMapper(Plan::class, null, $hydrator);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

        $collection = $mapper->all();

        $this->assertEquals('free', $collection[1]->name);
    }

    public function testStore()
    {
        $instance = new Manager();

        $pdo = $this->pdo;

        $instance->addConnection($pdo);

        $mapper = new PlanMapper(Plan::class);

        $instance->addMapper(Plan::class, $mapper);

        $mapper = $instance->getMapper(Plan::class);

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
