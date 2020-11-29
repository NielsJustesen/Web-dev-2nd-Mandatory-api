<?php

    require_once('src/DB/connection.php');


    class Album extends DB {

        function Create($title, $artistId){

            try {
            
                $query =<<<'SQL'
                    INSERT INTO album (Title, ArtistId) VALUES (?, ?)
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$title, $artistId]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0) {
                    return ["Message: Album created", "status: 201", "Title: ".$title];
                }
                

            } catch (\PDOException $e) {
                return $e->getMessage();
            }

        }

        function Read(){

        }

        function BrowseAlbums($order, $name){

            switch ($order) {
                case 'title':
                    try {
                        $query = <<<'SQL'
                            SELECT * FROM album WHERE title = ?;
                        SQL;
                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute([$name]);
                        $results = $stmt->fetch();
                        $this->disconnect();
                        return $results;
                    } catch (\PDOException $e) {
                        return $e->getMessage();
                    }
                    break;
                    
                case 'artist':
                    try {
                        $query = <<<'SQL'
                            SELECT *
                            FROM album
                            LEFT JOIN artist 
                            ON album.ArtistId = artist.ArtistId 
                            WHERE artist.Name = ?;
                        SQL;
                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute([$name]);
                        $results = $stmt->fetchAll();
                        $this->disconnect();
                        return $results;
                    } catch (\PDOException $e) {
                        return $e->getMessage();
                    }
                    break;
            }
        }

        function List(){
            try {
                $query = <<<'SQL'
                    SELECT title
                    FROM album
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
                $results = $stmt->fetchAll();
                $this->disconnect();

                return $results;
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function Update($id, $title){
            try {
                
                $query =<<<'SQL'
                    UPDATE Album SET Title = ? WHERE AlbumId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$title, $id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    return ["Status: 201", "Album title updated", "New title: ".$title];
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
                    DELETE FROM track WHERE AlbumId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();

                $query =<<<'SQL'
                    DELETE FROM album WHERE AlbumId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    return ["Status: 200", "Album deleted"];
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