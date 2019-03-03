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
}
