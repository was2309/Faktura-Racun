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

    require_once 'Invoice.php';
    require_once 'InvoiceItem.php';
    include 'connection.php';

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
                    $item = new InvoiceItem();
                    $item->setInvoiceNumber($row['invoice_number']);
                    $item->setItemName($row['item_name']);
                    $item->setQuantity($row['quantity']);
                    array_push($items, $item);
                }
            }

            $invoice->setItems($items);

        }
    }

    $conn->close();

?>
    <div class="links">
        <a href="index.php">Unos fakture</a>
    </div>

    <div id="invoiceForm">
        <label class="title"> Pretraga fakture </label>

        <form method="post" action="" name="invoiceF">
            <div class="input_group">
                <label>Broj računa: </label>
                <input type="number" name="invoiceNumber"  class="input_invoice_number" value="<?php
                echo $invoice->getInvoiceNumber();
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <label><?php
                    echo $invoice->getDate();
                    ?></label><br>
            </div>
            <div class="input_group">
                <label>Broj računa: </label>
                <label><?php
                echo $invoice->getOrganization();
                    ?></label><br>
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
                    </tr>
                <?php } ?>
                </thead>
                <tbody>

                </tbody>
            </table>
            <br><br>
        </div>


    </div>

</body>
</html>