<?php
    session_start();
//    include_once '../domain/Invoice.php';
//    include_once '../domain/InvoiceItem.php';
//    include '../repository/DBConnection.php';
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
    <title>Faktura | Pretraga</title>
    <link rel="icon" type="image/x-icon" href="../styles/images/fileIcon.png"">
</head>
<body>
<?php


//    if(isset($_POST['delete'])){
//        if(!isset($_SESSION['invoice'])){
//            echo '<script>alert("Molimo izaberite fakturu koju želite da obrišete!")</script>';
//            return;
//        }
//
//        $conn->autocommit(false);
//
//
//        $invoice = unserialize($_SESSION['invoice'], ['allowed_class' => Invoice::class]);
//
//        $conn->begin_transaction();
//
//        try{
//            $invoiceNumb = $invoice->getInvoiceNumber();
//            $sql_items = "DELETE FROM invoice_item WHERE invoice_number=?";
//            $stmt_items = $conn->prepare($sql_items);
////            foreach ($items as $item){
////                $invoiceNumb = $item->getInvoiceNumber();
//            $stmt_items->bind_param("i", $invoiceNumb);
//            $stmt_items->execute();
////            }
//
//            $sql_invoice = "DELETE FROM invoice WHERE invoice_number=?";
//            $stmt_invoice = $conn->prepare($sql_invoice);
//            $stmt_invoice->bind_param("i", $invoiceNumb);
//            $stmt_invoice->execute();
//
//            $conn->commit();
//            echo "Uspešno obirsana faktura!";
//
//        }catch(mysqli_sql_exception $exception){
//            $conn->rollback();
//            echo "Greška prilikom brisanja fakture!";
//            throw $exception;
//        } finally {
//            $conn->close();
//            session_unset();
//            $_POST = array();
//        }
//
//
//    }

include_once '../controller/InvoiceController.php';
include_once '../dto/DTOInvoice.php';
include_once '../dto/DTOItem.php';

$invoiceController = new InvoiceController();

$DTOInvoice = new DTOInvoice();

if(isset($_SESSION['dtoInvoice'])){
    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
}

if (isset($_POST['search'])) {
        if(empty($_POST['invoiceNumber'])) {
            echo '<script>alert("Molimo unesite broj fakture!")</script>';
            session_unset();
            $DTOInvoice = new DTOInvoice();
        } else {
            $invoiceNumber = $_POST['invoiceNumber'];
            $DTOInvoice = $invoiceController->findById($invoiceNumber);
            if($DTOInvoice->getInvoiceId() !== null){
                $_SESSION['invoiceNumber'] = $DTOInvoice->getInvoiceNumber();
                $_SESSION['date'] = $DTOInvoice->getDate();
                $_SESSION['organization'] = $DTOInvoice->getOrganization();
                $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
            }
        }
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

if(isset($_POST['removeItemBtn'])){
    $items = $DTOInvoice->getItems();
    foreach ($items as $item){
        if($item->getItemName() === $_POST['removeItemBtn']){
            if(!$item->isNew()){
                $item->setForDelete(true);
            }else{
                $DTOInvoice->removeItem($item);
            }
            $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
            break;
        }
    }
}

if(isset($_POST['update'])){
    if(!isset($_SESSION['dtoInvoice'])){
                echo '<script>alert("Molimo izaberite fakturu koju želite da izmenite!")</script>';
                return;
    }
    $newDate = $_POST['date'];
    $newOrganization = $_POST['organization'];
    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $DTOInvoice->setDate($newDate);
    $DTOInvoice->setOrganization($newOrganization);
    $DTOInvoice = $invoiceController->update($DTOInvoice);
    $_SESSION['invoiceNumber'] = $DTOInvoice->getInvoiceNumber();
    $_SESSION['date'] = $DTOInvoice->getDate();
    $_SESSION['organization'] = $DTOInvoice->getOrganization();
    $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
}

if(isset($_POST['delete'])) {
    if (!isset($_SESSION['dtoInvoice'])) {
        echo '<script>alert("Molimo izaberite fakturu koju želite da obrišete!")</script>';
        return;
    }

    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $invoiceController->delete($DTOInvoice);
    $DTOInvoice = new DTOInvoice();
}


?>
    <div class="links">
        <a href="index.php">Unos nove fakture</a>
    </div>
    <div id="invoiceForm">
        <label class="title"> Pretraga fakture </label>
        <div class="searchForms">
            <div class="searchPart">
                <form method="post" action="" name="searchInvoiceF" id="searchInvoiceF">
                    <div class="input_group">
                        <label>Broj računa: </label>
                        <input type="number" name="invoiceNumber"  class="input_invoice_number" value="<?php
                        if(!isset($_SESSION['invoiceNumber'])){
                            echo "";
                        } else{
                            echo $_SESSION['invoiceNumber'];
                        }
                        ?>"><br>
                    </div>
                    <button type="submit" name="search" value="search">Pretraži</button>
                </form>

            </div>
            <div class="searchPart">
                <form method="post" action="" name="invoiceF" id="updateInvoiceF">
                    <div class="input_group">
                        <label>Datum: </label>
                        <input type="date" name="date" id="date" value="<?php
                        if(!isset($_SESSION['date'])){
                            echo "";
                        } else{
                            echo $DTOInvoice->getDate();
                        }
                        ?>"><br>
                    </div>
                    <div class="input_group">
                        <label>Organizacija: </label>
                        <select name="organization" id="organization">
                            <option value=""></option>
                            <option value="Samsung" <?php
                            if(isset($_SESSION['organization']) && $DTOInvoice->getOrganization() === 'Samsung'){
                                echo ' selected';
                            }
                            ?>>Samsung</option>
                            <option value="Volvo" <?php
                            if(isset($_SESSION['organization']) && $DTOInvoice->getOrganization() === 'Volvo'){
                                echo ' selected';
                            }
                            ?>>Volvo</option>
                            <option value="Nestle"  <?php
                            if(isset($_SESSION['organization']) && $DTOInvoice->getOrganization() === 'Nestle'){
                                echo ' selected';
                            }
                            ?>>Nestle</option>
                            <option value="GSP"  <?php
                            if(isset($_SESSION['organization']) && $DTOInvoice->getOrganization() === 'GSP'){
                                echo ' selected';
                            }
                            ?>>GSP</option>
                        </select>
                    </div>

                    <br>
                    <div class="saveButtons">
                        <button type="submit" name="update" value="update" id="update" class="update_invoice">Sačuvaj izmene</button>
                        <button type="submit" name="delete" value="delete" id="delete" class="update_invoice"
                                onclick="return confirm('Da li ste sigurni da želite da obrišete fakturu?')" >Obriši fakturu</button>
                    </div>

                </form>
            </div>
            <br><br>
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
                    if(!$item->isForDelete()){
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
                <?php }
                } ?>
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