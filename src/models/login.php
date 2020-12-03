<?php

    require_once('src/DB/connection.php');

    class Login extends DB {

        function LoginCustomer($data){
            try {
              
                $qeury =<<<'SQL'
                    SELECT Password FROM customer WHERE customerId = ?
                SQL;

                $stmt = $this->pdo->prepare($qeury);
                $stmt->execute([$data['customerId']]);
                $result = $stmt->fetch();
                if(password_verify($data['enteredPassword'], $result['Password'])){
                    return "true";
                }
                else{
                    return "false";
                }
               
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }
    }

?>