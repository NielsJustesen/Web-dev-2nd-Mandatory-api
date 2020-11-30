<?php

    require_once('src/DB/connection.php');
    date_default_timezone_set('Europe/Copenhagen');
    class Invoice extends DB {

        function Create($data){

            try {
                
                $query =<<<'SQL'
                    INSERT INTO invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total)
                    VALUES (?,?,?,?,?,?,?,?)
                SQL;
                $now = date("Y-m-d H:i:s");
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$data['costumerId'], $now, $data['billindAddress'], $data['billingCity'], $data['billingState'], $data['billingCountry'], $data['billingPostalCode'], $data['total']]);
                $result = $stmt->rowCount();
                $this->disconnect();

                if($result < 1){
                    return "failed creating invoice";
                }
                else{
                    return "invoice created successfully";
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