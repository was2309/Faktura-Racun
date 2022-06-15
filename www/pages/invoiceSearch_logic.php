<?php
session_start();

include_once '../controller/InvoiceController.php';
include_once '../dto/DTOInvoice.php';
include_once '../dto/DTOItem.php';

$invoiceController = new InvoiceController();

$DTOInvoice = new DTOInvoice();

if (isset($_SESSION['dtoInvoice'])) {
    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
}

if (isset($_POST['search'])) {
    session_unset();
    if (empty($_POST['invoiceNumber'])) {
        echo '<script>alert("Molimo unesite broj fakture!")</script>';
    } else {
        $invoiceNumber = $_POST['invoiceNumber'];
        $DTOInvoice = $invoiceController->findById($invoiceNumber);
        if ($DTOInvoice !== null) {
            $_SESSION['invoiceNumber'] = $DTOInvoice->getInvoiceNumber();
            $_SESSION['date'] = $DTOInvoice->getDate();
            $_SESSION['organization'] = $DTOInvoice->getOrganization();
            $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
        }
    }
}

if (isset($_POST['saveItem'])) {
    if (!isset($_SESSION['invoiceNumber']) || $DTOInvoice === null) {
        echo "Molimo unesite fakturu pre nego što dodate stavku!";
        return;
    }
    $invoiceNumber = $_SESSION['invoiceNumber'];
    $itemName = $_POST['itemName'];
    $quantity = $_POST['quantity'];
    $DTOItem = new DTOItem();
    $DTOItem->setInvoiceNumber($invoiceNumber);
    $DTOItem->setIsNew(true);
    if (isset($_POST['itemName']) && $_POST['itemName'] !== "") {
        $DTOItem->setItemName($itemName);
    } else {
        echo " Molimo unesite naziv artikla! ";
    }

    if (isset($_POST['quantity']) && $_POST['quantity'] !== "") {
        $DTOItem->setQuantity($quantity);
    } else {
        echo " Molimo unesite količinu artikla! ";
    }

    if ($DTOItem->getInvoiceNumber() !== null && $DTOItem->getItemName() !== null && $DTOItem->getQuantity() !== null) {
        $DTOInvoice->addItem($DTOItem);
        $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
    }
}

if (isset($_POST['removeItemBtn'])) {
    $items = $DTOInvoice->getItems();
    foreach ($items as $item) {
        if ($item->getItemName() === $_POST['removeItemBtn']) {
            if (!$item->isNew()) {
                $item->setForDelete(true);
            } else {
                $DTOInvoice->removeItem($item);
            }
            $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
            break;
        }
    }
}

if (isset($_POST['update'])) {
    if (!isset($_SESSION['dtoInvoice'])) {
        echo '<script>alert("Molimo izaberite fakturu koju želite da izmenite!")</script>';
        return;
    }
    $newDate = $_POST['date'];
    $newOrganization = $_POST['organization'];
    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $DTOInvoice->setDate($newDate);
    $DTOInvoice->setOrganization($newOrganization);
    $DTOInv = $invoiceController->update($DTOInvoice);
    $_SESSION['invoiceNumber'] = $DTOInv->getInvoiceNumber();
    $_SESSION['date'] = $DTOInv->getDate();
    $_SESSION['organization'] = $DTOInv->getOrganization();
    $_SESSION['dtoInvoice'] = serialize($DTOInv);
}

if (isset($_POST['delete'])) {
    if (!isset($_SESSION['dtoInvoice'])) {
        echo '<script>alert("Molimo izaberite fakturu koju želite da obrišete!")</script>';
        return;
    }

    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $invoiceController->delete($DTOInvoice);
    $DTOInvoice = new DTOInvoice();
}

