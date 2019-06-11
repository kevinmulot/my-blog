<?php
namespace Model;
use Exception;
use PDO;

require_once dirname(__DIR__) .'./config/config.php';
abstract class Manager
{
    protected $db;
    /**
     * @return PDO
     * Function for database connection which uses data in config/config
     */
    public function connectDB()
    {
        try {
            $db = new PDO('mysql:host=' . HOST_NAME . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PWD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->db;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}

