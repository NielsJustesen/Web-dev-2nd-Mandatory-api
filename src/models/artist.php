<?php

    require_once("src/DB/connection.php");

    class Artist extends DB {

        function Create($name){

            try {
            
                $query =<<<"SQL"
                    INSERT INTO artist (Name) VALUES (?)
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name]);
                $result = $stmt->rowCount();
                if ($result > 0) {
                    $response = array("status"=>201, "message"=>"Artist created", "New Arist"=>$name);
                    return $response;
                }
                else {
                    $response = array("status"=>400, "message"=>"Artist was not created");
                    return $response;
                }
                $this->disconnect();
                

            } catch (\PDOException $e) {
                return $e->getMessage();
            }

        }

        function Read($id){
            try {

                $query = <<<"SQL"
                    SELECT *
                    FROM artist
                    WHERE ArtistId = ?
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
                    $response = array("status"=>400, "message"=>"Did not find Artist");
                    return $response;
                }
                
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function BrowseArtists($name){
            try {
                $query = <<<"SQL"
                    SELECT *
                    FROM artist
                    WHERE Name LIKE ? ORDER BY artist.Name
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(["%".$name."%"]);
                $results = $stmt->fetchAll();
                $this->disconnect();
                return $results;
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function List(){
            try {
                $query = <<<"SQL"
                    SELECT * FROM artist ORDER BY artist.Name
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

        function Update($artistData){
            try {
                
                $query =<<<"SQL"
                    UPDATE artist SET Name = ? WHERE ArtistId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$artistData["name"], $artistData["artistId"]]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    $data = array("Status"=>201,  "New name"=>$artistData["name"]);
                    return $data;
                }
                else {
                    $data = array("Status"=>400,  "message"=>"no artist was updated");
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
                    SELECT AlbumId FROM album WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->fetch();

                $query =<<<"SQL"
                    DELETE FROM track WHERE AlbumId = ?
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$result["AlbumId"]]);

                $query =<<<"SQL"
                    DELETE FROM album WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $query =<<<"SQL"
                    DELETE FROM artist WHERE ArtistId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();
                $this->pdo->commit();
                $this->disconnect();
                if ($result > 0){
                    return array("status"=>200, "message"=>"Artist deleted and all references");
                }
                else {
                    return array("status"=>400, "message"=>"Artist was not deleted");
                }
            } catch (\PDOException $e) {
                $this->pdo->rollBack();
                return $e->getMessage();
            }
        }
    }


?>