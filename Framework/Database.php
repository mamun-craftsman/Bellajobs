<?php
namespace Framework;
use PDO;
class Database {
    public $conn;
    
    public function __construct($config) {
        /**
         * function http_build_query
         * 
         * @param string $config
         * @param ' ' 
         * @param string $separator
         */
        $dsn = "mysql:".http_build_query($config, '', ';');
        $mood = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $mood);
        } catch (PDOException $e) {
            throw new Exception("Database Connection Failed: {$e->getMessage()}");
        }
    }
    
    public function query($query, $params=[]) {
        try {
            $stmt = $this->conn->prepare($query); 
            foreach ($params as $param=>$value) {
                $stmt->bindValue(':'.$param, $value );
            }
            $stmt->execute();
            return $stmt; 
        } catch (PDOException $th) {
            throw new Exception("Query failed to be executed: {$th->getMessage()}");
        }
    }
}

?>
