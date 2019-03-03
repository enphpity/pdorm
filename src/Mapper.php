<?php

namespace Enphpity\Pdorm;

use Enphpity\Pdorm\Contract\Hydrator\HydratorInterface;
use Enphpity\Pdorm\Contract\Query\QueryInterface;
use Enphpity\Pdorm\Builder\ResultBuilder;
use Enphpity\Pdorm\Hydrator\Hydrator;
use Enphpity\Pdorm\System\Connection;
use Enphpity\Pdorm\System\Query;
use Enphpity\Pdorm\Utility\Macroable;

class Mapper
{
    use Macroable;

    const PRIMARY_KEY = 'id';

    protected $entityClass;

    protected $connection = Connection::DEFAULT;

    protected $table = null;

    protected $primaryKey = self::PRIMARY_KEY;

    protected $properties = [];

    protected $hydrator = null;

    public function __construct(string $entityClass, QueryInterface $query = null, HydratorInterface $hydrator = null)
    {
        $this->entityClass = $entityClass;

        if (null === $query) {
            $manager = Manager::getInstance();
            
            $connection = $manager->getConnection($this->connection);

            $query = new Query($connection);
        }

        if (null === $hydrator) {
            $hydrator = new Hydrator();
        }

        $this->query = $query;
        $this->hydrator = $hydrator;
    }

    public function find($id)
    {
        $data = $this->getQuery()->select(
            $this->table,
            [
                $this->getPrimaryKey() => $id,
            ]
        )->fetch();

        $entity = ResultBuilder::buildSingleEntity($this, $data, $this->getHydrator());

        return $entity;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getHydrator()
    {
        return $this->hydrator;
    }
}
