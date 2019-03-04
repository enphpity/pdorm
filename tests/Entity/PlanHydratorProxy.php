<?php

namespace Enphpity\Pdorm\Test\Entity;

use Enphpity\Pdorm\Contract\Hydrator\HydratorInterface;

class PlanHydratorProxy extends Plan implements HydratorInterface
{
    public function hydrate(array $data, $object)
    {
        $object->id = $data['id'];
        $object->name = $data['name'];

        return $object;
    }

    public function extract($object)
    {
        return array('id' => $object->id, 'name' => $object->name);
    }
}
