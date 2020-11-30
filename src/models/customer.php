<?php

    require_once('src/DB/connection.php');

    class Customer extends DB {

        function Create($data){

            try {
                
                $qeury =<<<'SQL'
                    INSERT INTO customer (FirstName, LastName, Password, Company, Address, City, State, Country, PostalCode, Phone, Fax, Email)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
                SQL;
                $pwd = password_hash($data['password'], PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare($qeury);
                $stmt->execute([$data['firstName'], $data['lastName'], $pwd, $data['company'], $data['address'], $data['city'], $data['state'], $data['country'], $data['postalCode'], $data['phone'], $data['fax'], $data['email']]);
                $result = $stmt->rowCount();
                $this->disconnect();
                
                if ($result < 1){
                    return "Error creating costumer";
                }
                else{
                    return "Customer created successfully";
                }

            } catch (\PDOException $e) {
                return $e->getMessage();
            }


        }

        function Login($data){
            try {
                $qeury =<<<'SQL'
                    SELECT Email, Password FROM customer WHERE customerId = ?
                SQL;

                $stmt = $this->pdo->prepare($qeury);
                $stmt->execute([$data['customerId']]);
                $result = $stmt->fetch();

                if(password_verify($data['enteredPassword'], $result['Password'])){
                    return true;
                }
                else{
                    return false;
                }

            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        

        function Read(){
            
        }

        function Update($change, $data){

            switch ($change) {
                case 'password':

                    $qeury =<<<'SQL'
                        UPDATE customer SET Password = ? WHERE CustomerId = ?
                    SQL;
                    $pwd = password_hash($data['password'], PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare($qeury);
                    $stmt->execute([$pwd, $data['customerId']]);
                    $result = $stmt->rowCount();

                    if($result > 1){
                        return 'password was not changed';
                    }
                    else{
                        return 'password changed successfully';
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
            
        }

        function Delete(){
            
        }
    }

?>