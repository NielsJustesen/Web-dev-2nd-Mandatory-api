<?php

    class DB {

        protected $pdo;
        
        public function __construct() {

            // AWS db -h chinookabridgeddb.cxypwdfo5x68.us-east-1.rds.amazonaws.com -P 3306 -u admin -p
            $server = "chinookabridgeddb.cxypwdfo5x68.us-east-1.rds.amazonaws.com";
            $port = 3306;
            $dbName = "chinook_abridged";
            $user = "admin";
            $pwd = "chinookadmin";
            
            $dsn = "mysql:host=".$server."; port=".$port."; dbname=".$dbName."; charset=utf8";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            try {
                $this->pdo = @new PDO($dsn, $user, $pwd, $options); 
            } catch (\PDOException $e) {
                echo "Connection unsuccessful";
                die("Connection unsuccessful: " . $e->getMessage());
                exit();
            }
        }

        public function disconnect() {
            $this->pdo = null;
        }
    }
?>