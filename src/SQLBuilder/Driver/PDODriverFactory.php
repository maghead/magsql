<?php
namespace SQLBuilder\Driver;
use SQLBuilder\Driver\PDOMySQLDriver;
use SQLBuilder\Driver\PDOPgSQLDriver;
use SQLBuilder\Driver\PDOSqliteDriver;
use PDO;

class PDODriverFactory
{
    static public function create(PDO $pdo)
    {
        $driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        switch($driverName) {
            case "mysql":
                return new PDOMySQLDriver($pdo);
                break;
            case "pgsql":
                return new PDOPgSQLDriver($pdo);
                break;
            case "sqlite":
                return new PDOSqliteDriver($pdo);
                break;
        }
    }

}

