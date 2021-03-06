<?php
session_start();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="styles/style.css"/>
    <title>Faktura | Unos</title>

    <script>

        $(window).on("load", function () {
            $("#invoiceNumber")
                .change(() => {
                   var numberInvoice = $("#invoiceNumber").val();
                   $.post("./index.php", {invoiceNumber:numberInvoice});
                });
            $("#date")
                .change(() => {
                    var dateInvoice = $("#date").val();
                    $.post("./index.php", {date:dateInvoice});
                });
            $("#organization")
                .change(() => {
                    var orgInvoice = $("#organization").val();
                    $.post("./index.php", {organization:orgInvoice});
                });
        });

    </script>

</head>
<body>
<?php


require_once 'Invoice.php';
require_once 'InvoiceItem.php';
include_once 'createdb.php';
include_once 'createTables.php';
include 'connection.php';


$invoice = new Invoice();

if (isset($_SESSION['invoice'])) {
    $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => true]);
}

if(isset($_POST['invoiceNumber'])){
    if(empty($_POST['invoiceNumber'])){
        echo "<script>alert('Podaci o fakturi nisu validni!')</script>";
    }else{
        $invoice->setInvoiceNumber($_POST['invoiceNumber']);
        $_SESSION['invoice'] = serialize($invoice);
    }
}

if(isset($_POST['date'])){
    if(empty($_POST['date'])){
        echo "<script>alert('Podaci o fakturi nisu validni!')</script>";
    }else{
        $invoice->setDate($_POST['date']);
        $_SESSION['invoice'] = serialize($invoice);
    }
}

if(isset($_POST['organization'])){
    if(empty($_POST['organization'])){
        echo "<script>alert('Podaci o fakturi nisu validni!')</script>";
    }else{
        $invoice->setOrganization($_POST['organization']);
        $_SESSION['invoice'] = serialize($invoice);
    }
}

//if (isset($_POST['add'])) {
//    if (empty($_POST['invoiceNumber']) || $_POST['date'] === null || $_POST['organization'] === '') {
//        echo "<script>alert('Podaci o fakturi nisu validni!')</script>";
//    } else {
//        $invoice->setInvoiceNumber($_POST['invoiceNumber']);
//        $invoice->setDate($_POST['date']);
//        $invoice->setOrganization($_POST['organization']);
//
//        $_SESSION['invoice'] = serialize($invoice);
//    }
//}


if (isset($_POST['saveItem'])) {
    if(!isset($_SESSION['invoice']) || $invoice->getInvoiceNumber()===null){
        echo "<script>alert('Molimo unesite podatke o fakturi da biste uneli stavke!')</script>";

    }else{
        $item = new InvoiceItem($invoice->getInvoiceNumber());

        if ($_POST['itemName'] === '' || empty($_POST['quantity'])) {
            echo '<script>alert("Podaci o stavci nisu validni!")</script>';
        } else {
            $item->setItemName($_POST['itemName']);
            $item->setQuantity($_POST['quantity']);

            $invoice->addNewItem($item);

            $_SESSION['invoice'] = serialize($invoice);
        }
    }

}

if (isset($_POST['removeItemBtn'])) {
    $items = $invoice->getItems();
    foreach ($items as $item) {
        if ($item->getItemName() === $_POST['removeItemBtn']) {
            $invoice->removeItem($item);
            $_SESSION['invoice'] = serialize($invoice);
            break;
        }
    }
}


if (isset($_POST['save'])) {
    if (!isset($_SESSION['invoice'])) {
        echo '<script>alert("Molimo dodajte fakturu!")</script>';
    } else {

        $conn->autocommit(FALSE);

        $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => Invoice::class]);
        $sql_invoice = "INSERT INTO invoice (invoice_number, date, organization) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_invoice);
        $invoiceNumber = $invoice->getInvoiceNumber();
        $invoiceDate = $invoice->getDate();
        $invoiceOrganization = $invoice->getOrganization();
        $stmt->bind_param("iss", $invoiceNumber, $invoiceDate, $invoiceOrganization);
        $stmt->execute();

        $conn->begin_transaction();

        try {
            $items = $invoice->getItems();
            $sql_items = "INSERT INTO invoice_item (invoice_number, item_name, quantity) VALUES (?, ?, ?) ";
            $stmt_items = $conn->prepare($sql_items);
            foreach ($items as $item) {
                $invoiceNum = $item->getInvoiceNumber();
                $itemName = $item->getItemName();
                $itemQuantity = $item->getQuantity();
                $stmt_items->bind_param("isi", $invoiceNum, $itemName, $itemQuantity);
                $stmt_items->execute();
            }

            $conn->commit();
            echo "Uspe??no dodato u bazu!";

        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            echo "Gre??ka prilikom dodavanja fakture!";
            throw $exception;
        } finally {
            $conn->close();
            session_unset();
            $_POST = array();
            $invoice=new Invoice();
        }

    }
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
                <label>Broj ra??una: </label>
                <input type="number" name="invoiceNumber" id="invoiceNumber" class="input_invoice_number" value="<?php
                echo $invoice->getInvoiceNumber();
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <input type="date" name="date" id="date" value="<?php
                echo $invoice->getDate();
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Organizacija: </label><select name="organization" id="organization">
                    <option value=""></option>
                    <option value="Samsung" <?php
                    if ($invoice->getOrganization() === 'Samsung') {
                        echo ' selected';
                    }
                    ?>>Samsung
                    </option>
                    <option value="Volvo" <?php
                    if ($invoice->getOrganization() === 'Volvo') {
                        echo ' selected';
                    }
                    ?>>Volvo
                    </option>
                    <option value="Nestle" <?php
                    if ($invoice->getOrganization() === 'Nestle') {
                        echo ' selected';
                    }
                    ?>>Nestle
                    </option>
                    <option value="GSP" <?php
                    if ($invoice->getOrganization() === 'GSP') {
                        echo ' selected';
                    }
                    ?>>GSP
                    </option>
                </select>
            </div>
            <br>

    <!--        <button type="submit" name="add" value="add">Dodaj</button>-->
        </form>
        <br><br>
        <div class="save_invoice">
            <button type="submit" name="save" value="save" class="save_invoice" form="invoiceF">Sa??uvaj fakturu</button>
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
                    Koli??ina
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
                        <button type="submit" form="itemF" name="removeItemBtn"
                                value="<?php echo $item->getItemName() ?>">Ukloni
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
                <label>Koli??ina: </label><input type="number" name="quantity"><br>
                <button type="submit" name="saveItem" class="add_item">Sa??uvaj stavku</button>
            </div>
            <br><br>

        </form>

    </div>
</div>


</body>
</html>