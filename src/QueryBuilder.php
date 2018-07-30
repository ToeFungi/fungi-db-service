<?php namespace ToeFungi\QueryBuilder;

use stdClass;

class QueryBuilder implements IQueryBuilder
{
    private $query;

    private $method;

    private $tables;

    private $where;

    private $columns;

    private $join;

    public function select()
    {
        $this->method = 'SELECT';
        return $this;
    }

    public function setTables(array $tables)
    {
        $this->tables = $tables;
        return $this;
    }

    public function setTable(string $table)
    {
        $this->tables = $table;
        return $this;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function joinOn($tableJoin, $columnJoin, $tableOn, $columnOn)
    {
        $stdClass = new stdClass();

        $stdClass->tableColumnJoin = "{$tableJoin}.{$columnJoin}";
        $stdClass->tableJoin  = $tableJoin;
        $stdClass->columnJoin = $columnJoin;

        $stdClass->tableColumnOn = "{$tableOn}.{$columnOn}";
        $stdClass->tableOn = $tableOn;
        $stdClass->columnOn = $columnOn;

        $this->join = $stdClass;
        return $this;
    }

    public function whereEquals($table, $column, $value)
    {
        $stdClass = new stdClass();

        $stdClass->column = "{$table}.{$column}";
        $stdClass->operator = '=';
        $stdClass->value = $value;

        $this->where = $stdClass;
        return $this;
    }

    public function whereLike($table, $column, $value)
    {
        $stdClass = new stdClass();

        $stdClass->column = "{$table}.{$column}";
        $stdClass->operator = '%';
        $stdClass->value = $value;

        $this->where = $stdClass;
        return $this;
    }

    public function generate(): string
    {
        $this->query = $this->method;
        $this->query .= $this->getColumns();
        $this->query .= $this->getTables();
        $this->query .= $this->getWhere();

        return $this->query;
    }

    private function getWhere()
    {
        $whereClause = ' WHERE ';
        if (!$this->where) return '';

        if ($this->where->operator == '=') {
            $whereClause .= "{$this->where->column} = ";

            if (is_string($this->where->value)) {
                $whereClause .= "'{$this->where->value}'";
            } else {
                $whereClause .= "{$this->where->value}";
            }

            return $whereClause;
        } else if ($this->where->operator == '%') {
            $whereClause .= "{$this->where->column} LIKE ";

            if (is_string($this->where->value)) {
                $whereClause .= "'{$this->where->value}'";
            } else {
                $whereClause .= "{$this->where->value}";
            }

            return $whereClause;
        }

        return null;
    }

    private function getTables()
    {
        if (count($this->tables) === 1) {
            return $this->buildSingleTable();
        } else {
            return $this->buildMultipleTables();
        }
    }

    private function buildSingleTable()
    {
        return " FROM {$this->tables}";
    }

    /**
     * Builds an inner join for the query
     *
     * @return string
     */
    private function buildMultipleTables()
    {
        $tempTables = " FROM {$this->tables[0]}";
        $tempTables .= $this->getInnerJoin();
        return $tempTables;
    }

    /**
     * Builds an inner join for the query
     *
     * @return string
     */
    private function getInnerJoin()
    {
        return " INNER JOIN {$this->join->tableOn} ON {$this->join->tableColumnOn} = {$this->join->tableColumnJoin}";
    }

    /**
     * Builds the selection query
     *
     * @return string
     */
    private function getColumns()
    {
        if (count($this->tables) === 1) {
            return $this->buildSingleTableColumns();
        } else {
            return $this->buildMultipleTablesColumns();
        }
    }

    /**
     * Builds selection query for single table
     *
     * @return string
     */
    private function buildSingleTableColumns()
    {
        $tempColumns = '';

        for ($i = 0; $i < count($this->columns); $i++) {
            $tempColumns .= " {$this->tables}.{$this->columns[$i]}";

            if ($i !== count($this->columns) - 1) $tempColumns .= ',';
        }

        return $tempColumns;
    }

    /**
     * Builds selection query for multiple tables
     *
     * @return string
     */
    private function buildMultipleTablesColumns()
    {
        $tempColumns = '';

        $tableCount = count($this->tables);

        for ($i = 0; $i < $tableCount; $i++) {
            for ($j = 0; $j < count($this->columns[$i]); $j++) {
                $tempColumns .= " {$this->tables[$i]}.{$this->columns[$i][$j]}";

                if ($i !== $tableCount -1) $tempColumns .= ',';
            }
        }

        return $tempColumns;
    }
}