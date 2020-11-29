<?php

    class DB {
        protected $pdo;

        //Connect to the database
        public function __construct() {
            require_once('connectionData.php');
            
            $dsn = 'mysql:host=localhost; dbname=chinook_abridged; charset=utf8';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            try {
                $this->pdo = @new PDO($dsn, 'root', "", $options); 
            } catch (\PDOException $e) {
                echo 'Connection unsuccessful';
                die('Connection unsuccessful: ' . $e->getMessage());
                exit();
            }
        }

        //disconnect from the database
        public function disconnect() {
            $this->pdo = null;
        }
    }
?>