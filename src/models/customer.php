<?php

    require_once('src/DB/connection.php');

    class Customer extends DB {

        function Create($data){

            try {
                
                $qeury =<<<'SQL'
                    INSERT INTO customer (FirstName, LastName, Password, Company, Address, City, State, Country, PostalCode, Phone, Fax, Email)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
                SQL;

                $stmt = $this->pdo->prepare($qeury);
                $stmt->execute($data['firstName'], $data['lastName'], $data['password'], $data['company'], $data['address'], $data['city'], $data['state'], $data['country'], $data['postalCode'], $data['phone'], $data['fax'], $data['email']);
                $result = $stmt->rowCount();
                
                if ($result < 1){
                    return "Error creating costumer";
                }
                else{
                    return "Customer created: " . $data;
                }

            } catch (\PDOException $e) {
                return $e->getMessage();
            }


        }

        function Read(){
            
        }

        function Update(){
            
        }

        function Delete(){
            
        }
    }

?>