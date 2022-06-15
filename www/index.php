<?php
session_start();

$controllerName = null;
$methodName = null;
if (!empty($_REQUEST['c'])) {
    $controllerName = $_REQUEST['c'] . 'Controller';

    if ($_REQUEST['m']) {
        $methodName = $_REQUEST['m'];
    } else {
        $methodName = 'index';
    }
} else {
    $controllerName = 'IndexController';
    $methodName = 'index';
}

$controllerFileName = __DIR__ . '/controller/' . $controllerName . '.php';

if (!file_exists($controllerFileName)) {
    echo '404 - Page Not Found';
    return;
}

require_once $controllerFileName;

if (!class_exists($controllerName)) {
    echo '404 - Page Not Found';
    return;
}

$controller = new $controllerName;

$uri = $_SERVER['QUERY_STRING'];
$params = [];
parse_str($uri, $params);

unset($params['c']);
unset($params['m']);

if (is_callable([$controller, $methodName])) {
    call_user_func_array([$controller, $methodName], $params);
//    echo $controller->$methodName($_REQUEST['a']);
    return;
} else {
    echo '404 - Page Not Found';
    return;
}


require_once __DIR__ . '/dto/DTOInvoice.php';
require_once __DIR__ . '/dto/DTOItem.php';

require_once __DIR__ . '/controller/InvoiceController.php';


$invoiceController = new InvoiceController();
require_once __DIR__ . '/data/brands.php';

require_once __DIR__ . '/pages/invoice_input.php';
$DTOInvoice = new DTOInvoice();

if (isset($_SESSION['dtoInvoice'])) {
    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
}

if (isset($_POST['add'])) {
    $invoiceNumber = $_POST['invoiceNumber'];
    $date = $_POST['date'];
    $organization = $_POST['organization'];
    if (isset($_POST['invoiceNumber']) && $_POST['invoiceNumber'] !== "") {
        $DTOInvoice->setInvoiceNumber($invoiceNumber);
        $_SESSION['invoiceNumber'] = $invoiceNumber;
    } else {
        echo " Molimo unesite broj fakture! ";
    }

    if (isset($_POST['date']) && $_POST['date'] !== "") {
        $DTOInvoice->setDate($date);
        $_SESSION['date'] = $date;
    } else {
        echo " Molimo unesite datum! ";
    }

    if (isset($_POST['organization']) && $_POST['organization'] !== "") {
        $DTOInvoice->setOrganization($organization);
        $_SESSION['organization'] = $organization;
    } else {
        echo " Molimo izaberite organizaciju! ";
    }
    $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
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
    foreach ($items as $i) {
        if ($i->getItemName() === null || $i->getQuantity() === null) {
            return;
        }
        if ($i->getItemName() === $_POST['removeItemBtn']) {
            $DTOInvoice->removeItem($i);
            $_SESSION['dtoInvoice'] = serialize($DTOInvoice);
            break;
        }
    }
}

if (isset($_POST['save'])) {
    if (!isset($_SESSION['dtoInvoice'])) {
        echo '<script>alert("Molimo dodajte fakturu!")</script>';
        return;
    }

    $DTOInvoice = unserialize($_SESSION['dtoInvoice'], ['allowed_class' => true]);
    $invoiceController->save($DTOInvoice);
    $DTOInvoice = new DTOInvoice();
}
?>
