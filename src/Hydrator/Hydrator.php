<?php

namespace Enphpity\Pdorm\Hydrator;

use Enphpity\Pdorm\Contract\Hydrator\HydratorInterface;
use Enphpity\Pdorm\Utility\Str;
use ReflectionClass;

class Hydrator implements HydratorInterface
{
    protected $reflProperties = [];
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $reflProperties = $this->getRefectionProperities($object);

        foreach ($data as $key => $value) {
            $name = Str::camel($key);

            if (isset($reflProperties[$name])) {
                $reflProperties[$name]->setValue($object, $value);
            }
        }

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
    }

    protected function getRefectionProperities($object)
    {
        $class = get_class($object);

        if (isset($this->reflProperties[$class])) {
            return $this->reflProperties[$class];
        }

        $this->reflProperties[$class] = [];
        $reflClass = new ReflectionClass($class);
        $reflProperties = $reflClass->getProperties();

        foreach ($reflProperties as $property) {
            $property->setAccessible(true);
            $this->reflProperties[$class][$property->getName()] = $property;
        }

        return $this->reflProperties[$class];
    }
}
