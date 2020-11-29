<?php

    require_once('src/DB/connection.php');

    class Artist extends DB {

        function Create($name){

            try {
            
                $query =<<<'SQL'
                    INSERT INTO artist (Name) VALUES (?)
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name]);
                $result = $stmt->rowCount();
                if ($result > 0) {
                    return ["Message: Artist created", "status: 201", "Name: ".$name];
                }
                $this->disconnect();
                

            } catch (\PDOException $e) {
                return $e->getMessage();
            }

        }

        function Read(){

        }

        function BrowseArtists($name){

            $query = <<<'SQL'
                SELECT *
                FROM artist
                WHERE Name LIKE ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(["%".$name."%"]);
            $results = $stmt->fetchAll();
            $this->disconnect();
            return $results;
        }

        function List(){

            $query = <<<'SQL'
                SELECT Name FROM artist;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $this->disconnect();
            return $results;
        }

        function Update($id, $name){
            try {
                
                $query =<<<'SQL'
                    UPDATE Artist SET Name = ? WHERE ArtistId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name, $id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    return ["Status: 201", "Artist name updated", "New name: ".$name];
                }
                else {
                    return "Bad Request: 400";
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function Delete($id){
            try {

                $query =<<<'SQL'
                    DELETE FROM Track WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $query =<<<'SQL'
                    DELETE FROM Album WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $query =<<<'SQL'
                    DELETE FROM Artist WHERE ArtistId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    return ["Status: 200", "Artist deleted"];
                }
                else {
                    return "Bad Request: 400";
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }
    }


?>