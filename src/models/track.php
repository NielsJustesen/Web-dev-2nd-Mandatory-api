<?php

    require_once("src/DB/connection.php");

    class Track extends DB {

        function Create($name, $albumId, $mediaTypeId, $GenreId, $composer, $lengthMS, $bytes, $price){

            try {
            
                $query =<<<"SQL"
                    INSERT INTO track
                    (Name, AlbumId, MediaTypeId, GenreId, Composer, Bytes, Milliseconds, UnitPrice)
                    VALUES (?,?,?,?,?,?,?,?)
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name, $albumId, $mediaTypeId, $GenreId, $composer, $bytes, $lengthMS,  $price]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0) {
                    $response = array("status"=>201,"message"=>"Track Created");
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
                    FROM track
                    WHERE TrackId = ?
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
                    $response = array("status"=>400, "message"=>"Did not find Track");
                    return $response;
                }
                
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function GetPrice($songName){
            try {
                $query = <<<"SQL"
                    SELECT UnitPrice, TrackId FROM track WHERE Name = ?
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$songName]);
                $results = $stmt->fetch();
                
                $this->disconnect();
                return $results;
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function List(){
            try {
                $query = <<<"SQL"
                    SELECT * FROM track
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

        function BrowseTracks($order, $name){

            switch($order){

                case "album":
                    try {
                        $query = <<<"SQL"
                            SELECT track.*
                            FROM track
                            LEFT JOIN album
                            ON track.AlbumId = album.AlbumId 
                            WHERE album.Title = ?
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

                case "composer":
                    try {
                        $query = <<<"SQL"
                            SELECT track.*
                            FROM track
                            WHERE Composer LIKE  ?
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

                case "genre":
                    try {
                        $query = <<<"SQL"
                            SELECT track.*
                            FROM track
                            LEFT JOIN genre
                            ON track.GenreId = genre.GenreID
                            WHERE genre.Name = ?
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

                case "artist":
                    try {
                        $query = <<<"SQL"
                            SELECT track.*
                            FROM track
                            LEFT JOIN album
                            ON track.AlbumId = album.AlbumId
                            LEFT JOIN artist
                            ON album.ArtistId = artist.ArtistId
                            WHERE artist.Name LIKE ?
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

                default:
                    try {
                        $query = <<<"SQL"
                            SELECT * FROM track
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

        function Update($id, $trackData){
            try {
                
                $query =<<<"SQL"
                    UPDATE Track SET 
                    Name = ?,
                    AlbumId = ?,
                    MediaTypeId = ?,
                    GenreId = ?,
                    Composer = ?,
                    Milliseconds = ?,
                    Bytes = ?,
                    UnitPrice = ?
                    WHERE TrackId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$trackData["name"],$trackData["albumId"],$trackData["mediaTypeId"],$trackData["genreId"],$trackData["composer"],$trackData["milliseconds"],$trackData["bytes"],$trackData["unitPrice"], $id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    $data = array("Status"=>201,  "New name"=>$trackData["name"], "New albumId"=>$trackData["albumId"], "New mediatype"=>$trackData["mediaTypeId"], "New genreId"=>$trackData["genreId"], "New composer"=>$trackData["composer"], "New length"=>$trackData["milliseconds"], "New size"=>$trackData["bytes"], "New price"=>$trackData["unitPrice"]);
                    return $data;
                }
                else {
                    $data = array("Status"=>400,  "message"=>"no track was updated");
                    return $data;
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function Delete($id){
            try {
                
                $query =<<<"SQL"
                    DELETE FROM Track WHERE TrackId = ?
                SQL;
            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $result = $stmt->rowCount();
                $this->disconnect();
                if ($result > 0){
                    $response = array("status"=>200, "message"=>"Track deleted");
                    return $response;
                }
                else {
                    $response = array("status"=>400, "message"=>"track was not deleted");
                    return $response;
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }
    }
?>