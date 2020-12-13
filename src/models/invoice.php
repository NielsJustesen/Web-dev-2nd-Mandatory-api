<?php

    require_once('src/DB/connection.php');
    date_default_timezone_set('Europe/Copenhagen');
    class Invoice extends DB {

        function Create($data){

            try {
                $this->pdo->beginTransaction();
                
                $query =<<<'SQL'
                    INSERT INTO invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total)
                    VALUES (?,?,?,?,?,?,?,?)
                SQL;
                $now = date("Y-m-d H:i:s");
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$data['customerId'], $now, $data['billindAddress'], $data['billingCity'], $data['billingState'], $data['billingCountry'], $data['billingPostalCode'], $data['total']]);
                $result = $stmt->rowCount();

                if($result < 1){
                    return "failed creating invoice";
                }
                else{
                    $query =<<<'SQL'
                        SELECT InvoiceId FROM invoice WHERE InvoiceDate = ?
                    SQL;
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([$now]);
                    $result = $stmt->fetch();
                    $this->pdo->commit();
                    $this->disconnect();

                    if($result){
                        return $result;
                    }
                    else
                    {
                        return "failed to get invoice";
                    }
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