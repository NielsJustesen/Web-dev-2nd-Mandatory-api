<?php

    class DB {

        protected $pdo;
        
        public function __construct() {
            // Local DB
            $server = "localhost";
            $dbName = "chinook_abridged";
            $user = "root";
            $pwd = "";

            // AWS db
            // $server = "dbfilms.cqnxkgorpvhz.us-east-1.rds.amazonaws.com";
            // $dbName = "films";
            // $user = "adminfilms";
            // $pwd = "technology";
            
            $dsn = "mysql:host=".$server."; dbname=".$dbName."; charset=utf8";
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