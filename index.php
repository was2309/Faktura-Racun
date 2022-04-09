<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css" />
    <title>Faktura</title>
</head>
<body>
<?php
require_once 'Invoice.php';
require_once 'InvoiceItem.php';
session_start();

$invoice = new Invoice();

if (isset($_SESSION['invoice'])) {
    $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => true]);
}

if (isset($_POST['save'])) {
    $invoice->setInvoiceNumber($_POST['invoiceNumber']);
    $invoice->setDate($_POST['date']);
    $invoice->setOrganization($_POST['organization']);

    $_SESSION['invoice'] = serialize($invoice);
}


if (isset($_POST['saveItem'])) {
    $item = new InvoiceItem($invoice);

    if ($_POST['itemName'] === null || $_POST['quantity'] === null) {
        echo 'podaci nisu validni';
    } else {
        $item->setItemName($_POST['itemName']);
        $item->setQuantity($_POST['quantity']);
    }

    $invoice->addNewItem($item);

    $_SESSION['invoice'] = serialize($invoice);
}

?>


    <div id="invoiceForm">
        <label class="title"> Unos nove fakture </label>
        <form method="post" action="" name="invoiceF">
            <label>Broj računa: </label><input type="number" name="invoiceNumber" value="<?php
                                                                            echo $invoice->getInvoiceNumber();
                                                                            ?>"><br>
            <label>Datum: </label><input type="date" name="date" value="<?php
                                                         echo $invoice->getDate();
                                                         ?>"><br>
            <label>Organizacija: </label><select name="organization">
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
            </select><br>
            <button type="submit" name="save" value="save">Sačuvaj</button>
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
                    </tr>
                <?php } ?>
                </thead>
                <tbody>

                </tbody>
            </table>
            <br><br>
        </div>

        <div id="itemForm">
            <form method="post" action="" name="itemF">
                <label>Naziv artikla: </label><input type="text" name="itemName"><br>
                <label>Količina: </label><input type="number" name="quantity"><br>
                <button type="submit" name="saveItem">Sačuvaj stavku</button>
            </form>
        </div>
    </div>

</body>
</html>