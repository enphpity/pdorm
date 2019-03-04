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
            $hydrator = $this->getHydrator();
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

        $hydator = $this->getHydrator();

        $entity = ResultBuilder::buildSingleEntity($this, $data, $hydator);

        return $entity;
    }

    public function or(array $or = [])
    {
        $data = $this->getQuery()->select($this->table, $or, 'OR')->fetchAll();

        $hydator = $this->getHydrator();

        $collection = ResultBuilder::buildCollectionEntity($this, $data, $hydator);

        return $collection;
    }

    public function where(array $where = [])
    {
        $data = $this->getQuery()->select($this->table, $where)->fetchAll();

        $hydator = $this->getHydrator();

        $collection = ResultBuilder::buildCollectionEntity($this, $data, $hydator);

        return $collection;
    }

    public function all()
    {
        return $this->where();
    }

    public function store($entity)
    {
        if (is_array($entity)) {
            foreach ($entity as $item) {
                $this->store($item);
            }
        } elseif (is_object($entity)) {
            $entity = $this->storeEntity($entity);
        }

        return $entity;
    }

    public function storeEntity($entity)
    {
        $hydator = $this->getHydrator();

        $data = $hydator->extract($entity);

        $primaryKey = $this->getPrimaryKey();

        if (isset($data[$primaryKey])) {
            $this->update($data);
        } else {
            $id = $this->insert($data);

            $data[$primaryKey] = $id;

            $entity = ResultBuilder::buildSingleEntity($this, $data, $hydator);
        }

        return $entity;
    }

    public function insert(array $data)
    {
        return $this->getQuery()->insert($this->table, $data);
    }

    public function update(array $data)
    {
        $primaryKey = $this->getPrimaryKey();

        $id = $data[$primaryKey];

        $where = "$primaryKey = $id";

        return $this->getQuery()->update($this->table, $data, $where);
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator instanceof HydratorInterface ? $this->hydrator : new Hydrator();
    }
}
