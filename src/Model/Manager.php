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
    static protected $dtb = null;

    /**
     * @return PDO
     * Function for database connection which uses data in config/config
     */
    public function connectDB()
    {
        require_once '../config/config.php';

        if ((self::$dtb) === null) {
            $dtb = new PDO('mysql:host=' . HOST_NAME . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PWD);
            $dtb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$dtb = $dtb;
        }
        return self::$dtb;
    }
}
