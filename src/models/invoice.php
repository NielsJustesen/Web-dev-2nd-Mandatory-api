<?php

    require_once("src/DB/connection.php");
    date_default_timezone_set("Europe/Copenhagen");
    class Invoice extends DB {

        function Create($data){
            try {
                $this->pdo->beginTransaction();
                
                $query =<<<"SQL"
                    INSERT INTO invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total)
                    VALUES (?,?,?,?,?,?,?,?)
                SQL;
                $now = date("Y-m-d H:i:s");
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$data["customerId"], $now, $data["billindAddress"], $data["billingCity"], $data["billingState"], $data["billingCountry"], $data["billingPostalCode"], $data["total"]]);
                
                $invoiceId = $this->pdo->lastInsertId();

                foreach ($data["invoiceLines"] as $value) {
                    $query =<<<"SQL"
                        INSERT INTO invoiceline (InvoiceId, Quantity, TrackId, UnitPrice)
                        VALUES (?,?,?,?)
                    SQL;
                    $lineStmt = $this->pdo->prepare($query);
                    $lineStmt->execute([$invoiceId, $value["quantity"], $value["trackId"], $value["unitPrice"]]);
                }

                $result = $stmt->rowCount();

                $this->pdo->commit();
                $this->disconnect();


                if($result < 1){
                    return "failed creating invoice!";
                }
                else {
                    return "invoice created!";
                }

            } catch (\PDOException $e) {
                $this->pdo->rollBack();
                return $e->getMessage();
            }
        }
    }
?>