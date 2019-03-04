<?php

namespace Enphpity\Pdorm\Builder;

use Enphpity\Pdorm\Contract\Hydrator\HydratorInterface;
use Enphpity\Pdorm\Mapper;
use ReflectionClass;

class ResultBuilder
{
    public static function buildSingleEntity(Mapper $mapper, array $data, HydratorInterface $hydrator)
    {
        $entityClass = $mapper->getEntityClass();

        $reflection = new ReflectionClass($entityClass);

        $entity = $reflection->newInstanceWithoutConstructor();

        return $hydrator->hydrate($data, $entity);
    }

    public static function buildCollectionEntity(Mapper $mapper, array $data, HydratorInterface $hydrator)
    {
        $collection = [];

        $primaryKey = $mapper->getPrimaryKey();

        foreach ($data as $row => $columns) {
            $collection[$columns[$primaryKey]] = self::buildSingleEntity($mapper, $columns, $hydrator);
        }

        return $collection;
    }
}
