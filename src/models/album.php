<?php

    require_once("src/DB/connection.php");


    class Album extends DB {

        function Create($title, $artistId){

            try {
            
                $query =<<<"SQL"
                    INSERT INTO album (Title, ArtistId) VALUES (?, ?)
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$title, $artistId]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0) {
                    $response = array("status"=>201, "message"=>"Album created", "New Album"=>$title);
                    return $response;
                }
                

            } catch (\PDOException $e) {
                return $e->getMessage();
            }

        }

        function Read($id){

            try {

                $query = <<<"SQL"
                    SELECT *
                    FROM album
                    WHERE AlbumId = ?
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->fetch();

                $this->disconnect();
                if ($result)
                {
                    return $result;
                }
                else
                {
                    $response = array("status"=>400, "message"=>"Did not find Album");
                    return $response;
                }

            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function BrowseAlbums($order, $name){

            switch ($order) {
                case "title":
                    try {
                        $query = <<<"SQL"
                            SELECT artist.Name, album.*
                            FROM album
                            WHERE title LIKE ?
                            ORDER BY album.Title
                        SQL;
                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute(["%".$name."%"]);
                        $results = $stmt->fetch();
                        $this->disconnect();
                        return $results;
                    } catch (\PDOException $e) {
                        return $e->getMessage();
                    }
                    break;
                    
                case "artist":
                    try {
                        $query = <<<"SQL"
                            SELECT artist.Name, album.*
                            FROM album
                            LEFT JOIN artist 
                            ON album.ArtistId = artist.ArtistId 
                            WHERE artist.Name LIKE ?
                            ORDER BY album.Title
                        SQL;
                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute(["%".$name."%"]);
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
                $query = <<<"SQL"
                    SELECT artist.Name, album.*
                    FROM album
                    LEFT JOIN artist ON album.ArtistId = artist.ArtistId ORDER BY artist.Name
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

        function Update($id, $albumData){
            try {
                
                $query =<<<"SQL"
                    UPDATE album SET
                    Title = ?,
                    ArtistId = ?
                    WHERE AlbumId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$albumData["title"], $albumData["artistId"], $id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    $data = array("Status"=>201,  "New Title"=>$albumData["title"], "New ArtistId"=>$albumData["artistId"]);
                    return $data;
                }
                else {
                    $data = array("Status"=>400,  "message"=>"no album was updated");
                    return $data;
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function Delete($id){
            try {
                $this->pdo->beginTransaction();

                $query =<<<"SQL"
                    DELETE FROM track WHERE AlbumId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();

                $query =<<<"SQL"
                    DELETE FROM album WHERE AlbumId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();

                $this->pdo->commit();
                $this->disconnect();
                if ($result > 0){
                    $response = array("status"=>200, "message"=>"Album deleted");
                    return $response;
                }
                else {
                    $response = array("status"=>400, "message"=>"Album was not deleted");
                    return $response;
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }
    }


?>