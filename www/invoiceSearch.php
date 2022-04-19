<?php
    session_start();
    include_once 'Invoice.php';
    include_once 'InvoiceItem.php';
    include 'connection.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css" />
    <title>Faktura | Pretraga</title>
</head>
<body>
<?php

    $invoice = new Invoice();

    if (isset($_POST['search'])) {
        if(empty($_POST['invoiceNumber'])) {
            echo '<script>alert("Molimo unesite broj fakture!")</script>';
        } else {
            $sql_invoice = "SELECT * FROM invoice WHERE invoice_number=?";
            $stmt = $conn->prepare($sql_invoice);
            $stmt->bind_param("i", $_POST['invoiceNumber']);
            $stmt->execute();

            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $invoice->setInvoiceNumber($row['invoice_number']);
                    $invoice->setDate($row['date']);
                    $invoice->setOrganization($row['organization']);
                }
            }else{
                echo "<script>alert('Ne postoji račun sa navedenim brojem! ')</script>";
            }

            $sql_invoice_item = "SELECT * FROM invoice_item WHERE invoice_number=?";
            $stmt_item = $conn->prepare($sql_invoice_item);
            $stmt_item->bind_param("i", $_POST['invoiceNumber']);
            $stmt_item->execute();

            $result_item = $stmt_item->get_result();
            $items = array();

            if($result_item->num_rows > 0){
                while($row = $result_item->fetch_assoc()){
                    $item = new InvoiceItem($row['invoice_number']);
                    $item->setItemID($row['item_id']);
                    $item->setItemName($row['item_name']);
                    $item->setQuantity($row['quantity']);
                    $item->setIsNew(false);
                    $items[] = $item;
                }
            }

            $invoice->setItems($items);
            $conn->close();
            $_SESSION['invoice'] = serialize($invoice);
        }

    }

    if (isset($_SESSION['invoice'])) {
        $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => true]);
    }

    if (isset($_POST['saveItem'])) {
        $item = new InvoiceItem($invoice->getInvoiceNumber());

        if ($_POST['itemName'] === '' || empty($_POST['quantity'])) {
            echo '<script>alert("Podaci o stavci nisu validni!")</script>';
        }else {
            $item->setItemName($_POST['itemName']);
            $item->setQuantity($_POST['quantity']);
            $item->setIsNew(true);
            $invoice->addNewItem($item);

            $_SESSION['invoice'] = serialize($invoice);
        }
    }


    if(isset($_POST['removeItemBtn'])){
        $items = $invoice->getItems();
        foreach($items as $item){
            if($item->getItemName() === $_POST['removeItemBtn']){
                if(!$item->isNew()){
                    $item->setForDelete(true);
                    $_SESSION['itemForDelete'] = serialize($item);
                    $_SESSION['removingItems'][] = $_SESSION['itemForDelete'];
                }
                $invoice->removeItem($item);
                $_SESSION['invoice'] = serialize($invoice);
                break;
            }
        }
    }

    if(isset($_POST['update'])){
        if(!isset($_SESSION['invoice'])){
            echo '<script>alert("Molimo dodajte fakturu koju želite da izmenite!")</script>';
        }else{

            $conn->autocommit(false);

            $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => Invoice::class]);
            if(isset($_POST['date'])){
                $invoice->setDate($_POST['date']);
            }
            if(isset($_POST['organization'])){
                $invoice->setOrganization($_POST['organization']);
            }
            $sql_invoice = "UPDATE invoice SET date=?, organization=? WHERE invoice_number=?";
            $stmt = $conn->prepare($sql_invoice);
            $invoiceNumber = $invoice->getInvoiceNumber();
            $invoiceDate = $invoice->getDate();
            $invoiceOrganization = $invoice->getOrganization();
            $stmt->bind_param("ssi",$invoiceDate, $invoiceOrganization, $invoiceNumber);
            $stmt->execute();

            $conn->begin_transaction();

            try{
                $items = $invoice->getItems();
                $sql_insertItems = "INSERT INTO invoice_item (invoice_number, item_name, quantity) VALUES (?, ?, ?)";
                $stmt_insertItems = $conn->prepare($sql_insertItems);
                foreach ($items as $item){
                    if($item->isNew()){
                        $invoiceNum = $item->getInvoiceNumber();
                        $itemName = $item->getItemName();
                        $itemQuantity = $item->getQuantity();
                        $stmt_insertItems->bind_param("isi",$invoiceNum,  $itemName, $itemQuantity);
                        $stmt_insertItems->execute();
                    }
                }

                if(!isset($_SESSION['removingItems']) || !empty($_SESSION['removingItems'])){
                    $sql_deleteItems = "DELETE FROM invoice_item WHERE item_id=?";
                    $stmt_deleteItems = $conn->prepare($sql_deleteItems);
                    foreach ($_SESSION['removingItems'] as $item){
                        $itemForDelete = unserialize($item, ['allowed_class' => true]);
                        $itemID = $itemForDelete->getItemID();
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
            }
        }
    }

?>
    <div class="links">
        <a href="index.php">Unos nove fakture</a>
    </div>

    <div id="invoiceForm">
        <label class="title"> Pretraga fakture </label>

        <form method="post" action="" name="invoiceF" id="invoiceF">
            <div class="input_group">
                <label>Broj računa: </label>
                <input type="number" name="invoiceNumber"  class="input_invoice_number" value="<?php
                echo $invoice->getInvoiceNumber();
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <input type="date" name="date" value="<?php
                echo $invoice->getDate();
                ?>"><br>
            </div>
            <div class="input_group">
                <label+>Organizacija: </label>
                <select name="organization">
                    <option value=""></option>
                    <option value="Samsung" <?php
                    if($invoice->getOrganization() === 'Samsung'){
                        echo ' selected';
                    }
                    ?>>Samsung</option>
                    <option value="Volvo" <?php
                    if($invoice->getOrganization() === 'Volvo'){
                        echo ' selected';
                    }
                    ?>>Volvo</option>
                    <option value="Nestle"  <?php
                    if($invoice->getOrganization() === 'Nestle'){
                        echo ' selected';
                    }
                    ?>>Nestle</option>
                    <option value="GSP"  <?php
                    if($invoice->getOrganization() === 'GSP'){
                        echo ' selected';
                    }
                    ?>>GSP</option>
                </select>
            </div>

            <br>
            <button type="submit" name="search" value="search">Pretraži</button>
        </form>
        <br><br>
    </div>

    <div class="items">
        <div id="itemsTable">
            <table>
                <thead>
                <tr>
                    <th>
                        Redni broj
                    </th>
                    <th>
                        Naziv artikla
                    </th>
                    <th>
                        Količina
                    </th>
                </tr>
                <?php
                $count = 0;
                foreach ($invoice->getItems() as $item) {
                    $count++;
                    ?>
                    <tr>
                        <td><?php
                            echo $count;
                            ?></td>
                        <td><?php
                            echo $item->getItemName();
                            ?></td>
                        <td><?php
                            echo $item->getQuantity();
                            ?></td>
                        <td class="removeItemBtnClass">
                            <button type="submit" form="itemF" name="removeItemBtn" value="<?php echo $item->getItemName()?>">Ukloni</button>
                        </td>
                    </tr>
                <?php } ?>
                </thead>
                <tbody>

                </tbody>
            </table>
            <br><br>
        </div>
        <div id="itemForm">
            <form method="post" action="" id="itemF">
                <div class="input_group">
                    <label>Naziv artikla: </label><input type="text" name="itemName">
                </div>
                <br>
                <div class="input_group">
                    <label>Količina: </label><input type="number" name="quantity"><br>
                    <button type="submit" name="saveItem" class="add_item">Sačuvaj stavku</button>
                </div>
                <br><br>
                <button type="submit" name="update" value="update" class="save_invoice">Sačuvaj izmenjenu fakturu</button>
            </form>
        </div>
    </div>

</body>
</html>