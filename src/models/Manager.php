<?php




use \config;
/**
 * Class database
 * @package Framework
 */
abstract class Manager
{

    private $pdo;
    /**
     * @var
     */
    private $db_name;
    /**
     * @var string
     */
    private $db_user;
    /**
     * @var string
     */
    private $db_pass;
    /**
     * @var string
     */
    private $db_host;

    /**
     * database constructor.
     * @param $db_name
     * @param string $db_user
     * @param string $db_pass
     * @param string $db_host
     */
    public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    /**
     * @return PDO
     */
    private function getPDO()
    {
        if ($this->pdo === null) {

            $pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo = $pdo;
        }

        return $pdo;

    }

        // Exécute une requête SQL éventuellement paramétrée
        protected function executeRequest($sql, $params = null) {
            if ($params == null) {
                $res = $this->getPDO()->query($sql);    // exécution directe
            }
            else {
                $res = $this->getPDO()->prepare($sql);  // requête préparée
                $res->execute($params);
            }
            return $res;
        }


}