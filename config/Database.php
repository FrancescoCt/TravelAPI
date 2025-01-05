<?php
class Database {
    private $host; #= '127.0.0.1';
    private $db_name; #= 'travelAPI';
    private $db_user; #= 'developer';
    private $db_password; #= 'developer';
    public $conn;

    public function getConnection() {

        // Load variables from .env file
        $this->loadEnv(__DIR__ . '/.env');
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->db_user = getenv('DB_USER');
        $this->db_password = getenv('DB_PASS');

        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname='.$this->db_name.';charset=utf8',$this->db_user,$this->db_password);
            
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
    private function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception("File .env does not exists");
        }
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
    
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
    
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
    
    
}
