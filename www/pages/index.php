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
    if(isset($_POST['invoiceNumber']) && $_POST['invoiceNumber'] !== ""){
        $DTOInvoice->setInvoiceNumber($invoiceNumber);
        $_SESSION['invoiceNumber'] = $invoiceNumber;
    }else{
        echo " Molimo unesite broj fakture! ";
    }

    if(isset($_POST['date']) && $_POST['date'] !== ""){
        $DTOInvoice->setDate($date);
        $_SESSION['date'] = $date;
    }else{
        echo " Molimo unesite datum! ";
    }

    if(isset($_POST['organization']) && $_POST['organization'] !== ""){
        $DTOInvoice->setOrganization($organization);
        $_SESSION['organization'] = $organization;
    }else{
        echo " Molimo izaberite organizaciju! ";
    }
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
    $DTOItem->setIsNew(true);
    if(isset($_POST['itemName']) && $_POST['itemName'] !== ""){
        $DTOItem->setItemName($itemName);
    }else{
        echo " Molimo unesite naziv artikla! ";
    }

    if(isset($_POST['quantity']) && $_POST['quantity'] !== ""){
        $DTOItem->setQuantity($quantity);
    }
    else{
        echo " Molimo unesite količinu artikla! ";
    }

    if($DTOItem->getInvoiceNumber() !== null && $DTOItem->getItemName() !== null && $DTOItem->getQuantity() !== null){
        $DTOInvoice->addItem($DTOItem);
        $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
    }


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
    $DTOInvoice = new DTOInvoice();
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
                if(!isset($_SESSION['invoiceNumber'])){
                    echo "";
                } else{
                    echo $_SESSION['invoiceNumber'];
                }
                ?>" placeholder=""><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <input type="date" name="date" id="date" value="<?php
                if(!isset($_SESSION['date'])){
                    echo "";
                } else{
                    echo $_SESSION['date'];
                }
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Organizacija: </label><select name="organization" id="organization">
                    <option value=""></option>
                    <option value="Samsung" <?php
                    if (isset($_SESSION['organization']) && $_SESSION['organization'] === 'Samsung') {
                        echo ' selected';
                    }
                    ?>>Samsung
                    </option>
                    <option value="Volvo" <?php
                    if (isset($_SESSION['organization']) &&  $_SESSION['organization'] === 'Volvo') {
                        echo ' selected';
                    }
                    ?>>Volvo
                    </option>
                    <option value="Nestle" <?php
                    if (isset($_SESSION['organization']) && $_SESSION['organization'] === 'Nestle') {
                        echo ' selected';
                    }
                    ?>>Nestle
                    </option>
                    <option value="GSP" <?php
                    if (isset($_SESSION['organization']) && $_SESSION['organization'] === 'GSP') {
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