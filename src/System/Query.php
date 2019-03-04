<?php

namespace Enphpity\Pdorm\System;

use Enphpity\Pdorm\Contract\Connection\ConnectionInterface;
use Enphpity\Pdorm\Contract\Query\QueryInterface;
use PDO;
use PDOException;
use RunTimeException;

class Query implements QueryInterface
{
    private $connection = null;

    protected $statement;

    protected $fetchMode = PDO::FETCH_ASSOC;

    public function __construct(ConnectionInterface $connection)
    {
        $this->setConnection($connection);
    }

    public function getStatement()
    {
        if ($this->statement === null) {
            throw new PDOException("There is no PDOStatement object for use.");
        }
        return $this->statement;
    }

    public function prepare($sql, array $options = array())
    {
        try {
            $this->statement = $this->connection->prepare($sql, $options);

            return $this;
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }
    }
    public function execute(array $parameters = array())
    {
        try {
            $this->getStatement()->execute($parameters);
            return $this;
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }
    }
    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null)
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }

        try {
            return $this->getStatement()->fetch($fetchStyle, $cursorOrientation, $cursorOffset);
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }
    }
    public function fetchAll($fetchStyle = null, $column = 0)
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }

        try {
            return $fetchStyle === PDO::FETCH_COLUMN
                ? $this->getStatement()->fetchAll($fetchStyle, $column)
                : $this->getStatement()->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }
    }
    public function select($table, array $bind = [], $boolOperator = "AND")
    {
        if (!empty($bind)) {
            $where = array();
            foreach ($bind as $col => $value) {
                unset($bind[$col]);
                $bind[":" . $col] = $value;
                $where[] = $col . " = :" . $col;
            }
        }

        $sql = "SELECT * FROM " . $table . (($bind) ? " WHERE " . implode(" " . $boolOperator . " ", $where) : " ");

        $this->prepare($sql)
            ->execute($bind);
        return $this;
    }
    public function insert($table, array $bind)
    {
        $cols = implode(", ", array_keys($bind));
        $values = implode(", :", array_keys($bind));
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
        }

        $sql = "INSERT INTO " . $table . " (" . $cols . ")  VALUES (:" . $values . ")";

        $id = $this->prepare($sql)
            ->execute($bind)
            ->getLastInsertId();

        return intval($id);
    }
    public function update($table, array $bind, $where = "")
    {
        $set = array();
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
            $set[] = $col . " = :" . $col;
        }

        $sql = "UPDATE " . $table . " SET " . implode(", ", $set) . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute($bind)
            ->countAffectedRows();
    }
    public function delete($table, $where = "")
    {
        $sql = "DELETE FROM " . $table . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute()
            ->countAffectedRows();
    }

    public function raw(string $sql)
    {
        $this->prepare($sql)
            ->execute();
        if (false
             !== strpos(strtolower($sql), 'select') ||
            false !== strpos(strtolower($sql), 'show')
        ) {
            return $this->getStatement()->fetchAll($this->fetchMode);
        }
    }

    public function countAffectedRows()
    {
        try {
            return $this->getStatement()->rowCount();
        } catch (PDOException $e) {
            throw new RunTimeException($e->getMessage());
        }
    }

    public function getLastInsertId($name = null)
    {
        return $this->connection->lastInsertId($name);
    }

    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        $this->connection->connect();

        return $this;
    }
}
