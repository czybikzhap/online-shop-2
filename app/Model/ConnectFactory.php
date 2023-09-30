<?php

namespace App\Model;

use PDO;

class ConnectFactory
{
    private static PDO $pdo;

    public static function connectDB(): PDO
    {
        if (isset(static::$pdo)) {
            return static::$pdo;
        }
        static::$pdo = new PDO('pgsql:host=db; dbname=dbname',
            'dbuser', 'dbpwd');

        return static::$pdo;
    }
}