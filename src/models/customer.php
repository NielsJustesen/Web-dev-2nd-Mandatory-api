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

        

        

        function Read($email){

            try {
                $query =<<<'SQL'
                    SELECT * FROM customer WHERE email = ?
                SQL;
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$email]);
                $result = $stmt->fetch();
                $this->disconnect();

                return $result;
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }

        function Update($change, $data){

            switch ($change) {
                case 'password':
                    try {
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
                        $this->disconnect();

                    } catch (\PDOException $e) {
                        return $e->getMessage();
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