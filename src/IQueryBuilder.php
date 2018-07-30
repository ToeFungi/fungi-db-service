<?php namespace ToeFungi\QueryBuilder;

interface IQueryBuilder
{
    /**
     * Set SQL method to use when generating the query
     *
     * @return self
     */
    public function select();

    /**
     * Set the tables you want to query data from
     *
     * @param array $tables
     * @return self
     */
    public function setTables(array $tables);

    /**
     * Set the table you want to query data from
     *
     * @param string $table
     * @return self
     */
    public function setTable(string $table);

    /**
     * Set the columns you want to query data from. If using multiple tables
     * then each item in the array is an array of columns that correlate 1 for 1
     * to the setTables function
     *
     * @param array $columns
     * @return self
     */
    public function setColumns(array  $columns);

    /**
     * Create an INNER join for your SQL query when using multiple tables
     *
     * @param $tableJoin
     * @param $columnJoin
     * @param $tableOn
     * @param $columnOn
     * @return self
     */
    public function joinOn($tableJoin, $columnJoin, $tableOn, $columnOn);

    /**
     * Set the WHERE clause based on the table, column and value
     *
     * @param $table
     * @param $column
     * @param $value
     * @return self
     */
    public function whereEquals($table, $column, $value);

    /**
     * Set a fuzzy WHERE clause on the query based on the table, column and value
     *
     * @param $table
     * @param $column
     * @param $value
     * @return mixed
     */
    public function whereLike($table, $column, $value);

    /**
     * Generate the SQL query which is ready to use
     *
     * @return string
     */
    public function generate(): string;
}