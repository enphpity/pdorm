<?php

namespace Enphpity\Pdorm\Test;

use PHPUnit\Framework\TestCase;

class BaseCase extends TestCase
{
    protected $pdo = null;

    protected function setUp(): void
    {
        $this->pdo = new \PDO('sqlite::memory:', null, null, null);
        $this->pdo->query('CREATE TABLE IF NOT EXISTS plans (id INTEGER PRIMARY KEY, name TEXT NOT NULL);');
        $this->pdo->query('CREATE TABLE IF NOT EXISTS accounts (id INTEGER PRIMARY KEY, plan_id INTEGER NOT NULL);');
        $this->pdo->query("INSERT INTO plans (name) VALUES ('free');");
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}
