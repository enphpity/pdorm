<?php

namespace Enphpity\Pdorm\Contract\Connection;

interface ConnectionInterface
{
    public function connect();
    public function disconnect();
}
