<?php
    session_start();

?>

<!doctype html>
<html lang="en" style="height: 100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/style.css" />
    <title>Faktura | Unos</title>
    <link rel="icon" type="image/x-icon" href="../styles/images/fileIcon.png"">
</head>
<body>
<?php
//
//
//require_once '../domain/Invoice.php';
//require_once '../domain/InvoiceItem.php';
//include_once '../repository/createdb.php';
//include_once '../repository/createTables.php';
//include '../repository/connection.php';
//
//
//$invoice = new Invoice();
//
//if (isset($_SESSION['invoice'])) {
//    $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => true]);
//}
//
//if (isset($_POST['add'])) {
//    if(empty($_POST['invoiceNumber']) || $_POST['date'] === null || $_POST['organization']===''){
//        echo "<script>alert('Podaci o fakturi nisu validni!')</script>";
//    } else {
//        $invoice->setInvoiceNumber($_POST['invoiceNumber']);
//        $invoice->setDate($_POST['date']);
//        $invoice->setOrganization($_POST['organization']);
//
//        $_SESSION['invoice'] = serialize($invoice);
//    }
//}
//
//
//if (isset($_POST['saveItem'])) {
//    if(!isset($_SESSION['invoice']) || $invoice->getInvoiceNumber()===null){
//        echo "<script>alert('Molimo unesite podatke o fakturi da biste uneli stavke!')</script>";
//
//    }else {
//        $item = new InvoiceItem($invoice->getInvoiceNumber());
//
//        if ($_POST['itemName'] === '' || empty($_POST['quantity'])) {
//            echo '<script>alert("Podaci o stavci nisu validni!")</script>';
//        } else {
//            $item->setItemName($_POST['itemName']);
//            $item->setQuantity($_POST['quantity']);
//
//            $invoice->addNewItem($item);
//
//            $_SESSION['invoice'] = serialize($invoice);
//        }
//    }
//}
//
//if(isset($_POST['removeItemBtn'])){
//    $items = $invoice->getItems();
//    foreach($items as $item){
//        if($item->getItemName() === $_POST['removeItemBtn']){
//            $invoice->removeItem($item);
//            $_SESSION['invoice'] = serialize($invoice);
//            break;
//        }
//    }
//}
//
//
//if(isset($_POST['save'])){
//    if(!isset($_SESSION['invoice'])){
//        echo '<script>alert("Molimo dodajte fakturu!")</script>';
//    }else{
//
//        $conn->autocommit(FALSE);
//
//        $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => Invoice::class]);
//        $sql_invoice = "INSERT INTO invoice (invoice_number, date, organization) VALUES (?, ?, ?)";
//        $stmt = $conn->prepare($sql_invoice);
//        $invoiceNumber = $invoice->getInvoiceNumber();
//        $invoiceDate = $invoice->getDate();
//        $invoiceOrganization = $invoice->getOrganization();
//        $stmt->bind_param("iss", $invoiceNumber, $invoiceDate, $invoiceOrganization);
//        $stmt->execute();
//
//        $conn->begin_transaction();
//
//        try{
//            $items = $invoice->getItems();
//            $sql_items = "INSERT INTO invoice_item (invoice_number, item_name, quantity) VALUES (?, ?, ?) ";
//            $stmt_items = $conn->prepare($sql_items);
//            foreach ($items as $item){
//                $invoiceNum = $item->getInvoiceNumber();
//                $itemName = $item->getItemName();
//                $itemQuantity = $item->getQuantity();
//                $stmt_items->bind_param("isi",$invoiceNum,  $itemName, $itemQuantity);
//                $stmt_items->execute();
//            }
//
//            $conn->commit();
//            echo "Uspešno dodato u bazu!";
//
//        }catch (mysqli_sql_exception $exception){
//            $conn->rollback();
//            echo "Greška prilikom dodavanja fakture!";
//            throw $exception;
//        } finally {
//            $conn->close();
//            session_unset();
//            $_POST = array();
//        }
//
//    }
//}

include_once '../controller/InvoiceController.php';
include_once '../dto/DTOInvoice.php';
include_once '../dto/DTOItem.php';

 $invoiceController = new InvoiceController();

 $DTOInvoice = new DTOInvoice();

 if(isset($_SESSION['dtoInvoice'])){
     $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
 }

if (isset($_POST['add'])) {
    $invoiceNumber = $_POST['invoiceNumber'];
    $date = $_POST['date'];
    $organization = $_POST['organization'];
    $DTOInvoice->setInvoiceNumber($invoiceNumber);
    $DTOInvoice->setDate($date);
    $DTOInvoice->setOrganization($organization);
    $_SESSION['invoiceNumber'] = $invoiceNumber;
    $_SESSION['date'] = $date;
    $_SESSION['organization'] = $date;
    $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
}

if (isset($_POST['saveItem'])) {
    if(!isset($_SESSION['invoiceNumber']) || $DTOInvoice === null){
        echo "Molimo unesite fakturu pre nego što dodate stavku!";
        return;
    }
    $invoiceNumber = $_SESSION['invoiceNumber'];
    $itemName = $_POST['itemName'];
    $quantity = $_POST['quantity'];
    $DTOItem = new DTOItem();
    $DTOItem->setInvoiceNumber($invoiceNumber);
    $DTOItem->setItemName($itemName);
    $DTOItem->setQuantity($quantity);
    $DTOItem->setIsNew(true);
    $DTOInvoice->addItem($DTOItem);
    $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
//    $items = $_SESSION['items'];
//    if($items === null){
//        $items = array();
//        $items[] = array("ordNum"=>1, "itemName"=>$itemName, "quantity"=>$quantity);
//        return;
//    }
//    $items[] = array("ordNum"=>count($items), "itemName"=>$itemName, "quantity"=>$quantity);
//    $_SESSION['items'] = $items;
}

if (isset($_POST['removeItemBtn'])) {
    $items = $DTOInvoice->getItems();
    foreach ($items as $i){
        if($i->getItemName() === $_POST['removeItemBtn']){
            $DTOInvoice->removeItem($i);
            $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
            break;
        }
    }
}

if(isset($_POST['save'])){
    if(!isset($_SESSION['dtoInvoice'])){
            echo '<script>alert("Molimo dodajte fakturu!")</script>';
            return;
    }

    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $invoiceController->save($DTOInvoice);
}

?>

<div class="links">
    <a href="invoiceSearch.php">Pretraga fakture</a>
</div>
<div id="invoiceForm">

    <label class="title"> Unos nove fakture </label>
    <div id="invoiceInput">
        <form method="post" action="" name="invoiceF" id="invoiceF">
            <div class="input_group">
                <label>Broj računa: </label>
                <input type="number" name="invoiceNumber" id="invoiceNumber" class="input_invoice_number" value="<?php
                echo $invoiceNumber;
                ?>" placeholder=""><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <input type="date" name="date" id="date" value="<?php
                echo $date;
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Organizacija: </label><select name="organization" id="organization">
                    <option value=""></option>
                    <option value="Samsung" <?php
                    if ($organization === 'Samsung') {
                        echo ' selected';
                    }
                    ?>>Samsung
                    </option>
                    <option value="Volvo" <?php
                    if ($organization === 'Volvo') {
                        echo ' selected';
                    }
                    ?>>Volvo
                    </option>
                    <option value="Nestle" <?php
                    if ($organization === 'Nestle') {
                        echo ' selected';
                    }
                    ?>>Nestle
                    </option>
                    <option value="GSP" <?php
                    if ($organization === 'GSP') {
                        echo ' selected';
                    }
                    ?>>GSP
                    </option>
                </select>
            </div>
            <br>

                    <button type="submit" name="add" value="add">Dodaj</button>
        </form>
        <br><br>
        <div class="save_invoice">
            <button type="submit" name="save" value="save" class="save_invoice_button" form="invoiceF">Sačuvaj fakturu</button>
        </div>
    </div>
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
            foreach ($DTOInvoice->getItems() as $item) {
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
                        <button type="submit" form="itemF" name="removeItemBtn" value="<?php echo $item->getItemName()?>">
                            <i class="fa fa-times icon-large" aria-hidden="true"></i>
                        </button>
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
            <br>
        </form>

    </div>
</div>


</body>
</html>