<?php
include_once 'createdb.php';
include_once 'createTables.php';
include 'DBConnection.php';
include_once 'InvoiceRepository.php';
class InvoiceRepositoryMySQLImpl implements InvoiceRepository
{

    public function save(Invoice $invoice): void
    {
        $conn = DBConnection::getInstance()->connect();
        $conn->autocommit(FALSE);
        $invoiceNumber = $invoice->getInvoiceNumber();
        $invoiceDate = $invoice->getDate();
        $invoiceOrganization = $invoice->getOrganization();

        if($this->checkIfExists($invoiceNumber)){
            echo "Faktura sa navedenim brojem već postoji!";
            return;
        }

        $conn->begin_transaction();

        try{

            $sql_invoice = "INSERT INTO invoice (invoice_number, date, organization) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_invoice);
            $stmt->bind_param("iss", $invoiceNumber, $invoiceDate, $invoiceOrganization);
            $stmt->execute();

            $lastID = mysqli_insert_id($conn);


            $items = $invoice->getItems();
            $sql_items = "INSERT INTO invoice_item (invoice_id, item_name, quantity) VALUES (?, ?, ?) ";
            $stmt_items = $conn->prepare($sql_items);
            foreach ($items as $item){
                $invoiceId = $lastID;
                $itemName = $item->getItemName();
                $itemQuantity = $item->getQuantity();
                $stmt_items->bind_param("isi",$invoiceId, $itemName, $itemQuantity);
                $stmt_items->execute();
            }

            $conn->commit();
            echo "Uspešno dodato u bazu!";

        }catch (mysqli_sql_exception $exception){
            $conn->rollback();
            echo "Greška prilikom dodavanja fakture!";
            throw $exception;
        } finally {
            $conn->close();
            session_unset();
            $_POST = array();
        }
    }

    public function findById(int $invoiceNumber): Invoice
    {
        $conn = DBConnection::getInstance()->connect();
        $invoice = new Invoice();
        $sql_invoice = "SELECT * FROM invoice WHERE invoice_number=?";
        $stmt = $conn->prepare($sql_invoice);
        $stmt->bind_param("i", $invoiceNumber);
        $stmt->execute();

        $result = $stmt->get_result();
        $invoiceID = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $invoice->setInvoiceId($row['invoice_id']);
                $invoice->setInvoiceNumber($row['invoice_number']);
                $invoice->setDate($row['date']);
                $invoice->setOrganization($row['organization']);
            }
            $invoiceID = $invoice->getInvoiceId();
        }else{
            echo "<script>alert('Ne postoji faktura sa navedenim brojem! ')</script>";
            $conn->close();
            return $invoice;
        }

        $sql_invoice_item = "SELECT * FROM invoice_item WHERE invoice_id=?";
        $stmt_item = $conn->prepare($sql_invoice_item);
        $stmt_item->bind_param("i", $invoiceID);
        $stmt_item->execute();

        $result_item = $stmt_item->get_result();
        $items = array();

        if($result_item->num_rows > 0){
            while($row = $result_item->fetch_assoc()){
                $item = new InvoiceItem();
                $item->setInvoiceId($row['invoice_id']);
                $item->setItemID($row['item_id']);
                $item->setItemName($row['item_name']);
                $item->setQuantity($row['quantity']);
                $item->setIsNew(false);
                $items[] = $item;
            }
        }

        $invoice->setItems($items);
        $conn->close();

        return $invoice;
    }

    public function update(Invoice $invoice): Invoice
    {
        $conn = DBConnection::getInstance()->connect();
        $conn->autocommit(false);
        $conn->begin_transaction();
        $inv = new Invoice();
        $invoiceNumber = $invoice->getInvoiceNumber();
        if(!$this->checkIfExists($invoiceNumber)){
            echo "Tražena faktura ne postoji! ";
            return $inv;
        }
        try{
            $sql_invoice = "UPDATE invoice SET date=?, organization=? WHERE invoice_number=?";
            $stmt = $conn->prepare($sql_invoice);
            $invoiceId = $invoice->getInvoiceId();
            $invoiceDate = $invoice->getDate();
            $invoiceOrganization = $invoice->getOrganization();
            $stmt->bind_param("ssi",$invoiceDate, $invoiceOrganization, $invoiceNumber);
            $stmt->execute();


            $items = $invoice->getItems();
            $sql_insertItems = "INSERT INTO invoice_item (invoice_id, item_name, quantity) VALUES (?, ?, ?)";
            $stmt_insertItems = $conn->prepare($sql_insertItems);
            foreach ($items as $item){
                if($item->isNew()){
                    $itemName = $item->getItemName();
                    $itemQuantity = $item->getQuantity();
                    $stmt_insertItems->bind_param("isi", $invoiceId, $itemName, $itemQuantity);
                    $stmt_insertItems->execute();
                }
            }

            $sql_deleteItems = "DELETE FROM invoice_item WHERE item_id=?";
            $stmt_deleteItems = $conn->prepare($sql_deleteItems);
            foreach ($items as $item){
                if($item->isForDelete()){
                    $itemID = $item->getItemID();
                    $stmt_deleteItems->bind_param("i", $itemID);
                    $stmt_deleteItems->execute();
                }
            }


            $conn->commit();
            echo "Uspešno ažurirana faktura!";

        }catch(mysqli_sql_exception $exception){
            $conn->rollback();
            echo "Greška prilikom ažuriranja fakture!";
            throw $exception;
        } finally {
            $conn->close();
            session_unset();
            $_POST = array();
            return $invoice;
        }
    }

    public function delete(int $invoiceId, int $invoiceNumber): void
    {
        $conn = DBConnection::getInstance()->connect();
        $conn->autocommit(false);

        $conn->begin_transaction();

        if(!$this->checkIfExists($invoiceNumber)){
            echo "Tražena faktura ne postoji! ";
            return;
        }
        try{
            $sql_items = "DELETE FROM invoice_item WHERE invoice_id=?";
            $stmt_items = $conn->prepare($sql_items);
            $stmt_items->bind_param("i", $invoiceId);
            $stmt_items->execute();

            $sql_invoice = "DELETE FROM invoice WHERE invoice_id=?";
            $stmt_invoice = $conn->prepare($sql_invoice);
            $stmt_invoice->bind_param("i", $invoiceId);
            $stmt_invoice->execute();

            $conn->commit();
            echo "Uspešno obirsana faktura!";

        }catch(mysqli_sql_exception $exception){
            $conn->rollback();
            echo "Greška prilikom brisanja fakture!";
            throw $exception;
        } finally {
            $conn->close();
            session_unset();
            $_POST = array();
        }
    }

    public function checkIfExists(int $invoiceNumber): bool
    {
        $conn = DBConnection::getInstance()->connect();
        $sql_invoice = "SELECT * FROM invoice WHERE invoice_number=?";
        $stmt = $conn->prepare($sql_invoice);
        $stmt->bind_param("i", $invoiceNumber);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }
}