<?php

    require_once('src/DB/connection.php');

    class InvoiceLine extends DB {

        function Create($data){
            try {
                
                $query =<<<'SQL'
                    INSERT INTO invoiceline (InvoiceId, Quantity, TrackId, UnitPrice)
                    VALUES (?,?,?,?)
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$data['invoiceId'], $data['quantity'], $data['trackId'], $data['unitPrice']]);
                $result = $stmt->rowCount();
                $this->disconnect();

                if($result < 1){
                    return 'failed to create invoiceline';
                }
                else{
                    return 'invoceline created successfully';
                }
            } catch (\PDOException $e) {
                return $e->getMessage();
            }
        }
    }
?>