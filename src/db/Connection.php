<?php

namespace Perfluence\db;

class Connection
{
    protected $connection;
    public function __construct() {
        $this->connection = new \PDO( "mysql:dbname=perfluence;host=localhost",
            "perfluence",
            "perfluence" );
    }

    public function getConnection(){
        return $this->connection;
    }
}