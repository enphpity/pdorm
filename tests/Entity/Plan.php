<?php

namespace Enphpity\Pdorm\Test\Entity;

class Plan
{
    protected $id;

    public $name;

    public function getId()
    {
        return $this->id;
    }
}
