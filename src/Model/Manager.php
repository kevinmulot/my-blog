<?php

namespace Model;

use PDO;

/**
 * Class Manager
 * @package Model
 */
abstract class Manager
{
    /**
     * @var null
     */
    static protected $db = null;

    /**
     * @return PDO
     * Function for database connection which uses data in config/config
     */
    public function connectDB()
    {
        require_once '../config/config.php';

        if ((self::$db) === null) {
            $db = new PDO('mysql:host=' . HOST_NAME . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PWD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$db = $db;
        }
        return self::$db;
    }
}
