<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        include './Invoice.php';
        include './InvoiceItem.php';

        $invoice = new Invoice();

        if(isset($_POST['save'])){
            $signal = true;


            if($_POST['invoiceNumber'] === null){
               $signal=false;
            }else{
                $invoice->setInvoiceNumber($_POST['invoiceNumber']);
            }
            if(!isset($_POST['date']) || $_POST['date'] === null){
                $signal = false;
            }else{
                $invoice->setDate($_POST['date']);
            }
            if(!isset($_POST['organization']) || $_POST['organization'] === null){
               $signal = false;
            }else{
                $invoice->setOrganization($_POST['organization']);
            }

        }

        if(isset($_POST['saveItem'])){
            $signal = true;
            $item = new InvoiceItem($invoice);


            if($_POST['itemName'] === null){
                $signal = false;
            }else{
                $item->setItemName($_POST['itemName']);
            }
            if($_POST['quantity'] === null){
                $signal = false;
            }else{
                $item->setQuantity($_POST['quantity']);
            }

            if($signal){
                $invoice->addNewItem($item);
            }

        }
    ?>
    <form action="" method="post" name="racun">
       <label for="invoiceNumber"> Broj računa: </label> <input type="number" name="invoiceNumber" value="<?php
            echo $invoice->getInvoiceNumber();
        ?>"> <br>
        <label for="datum"> Datum računa: </label> <input type="date" name="date" value="<?php
            echo $invoice->getDate();
        ?>"> <br>
        <label for="organizacija"> Organizacija: </label> <select name="organization">
                        <option value=""></option>
                        <option value="samsung" <?php
                            if($invoice->getOrganization() === 'samsung'){
                                echo ' selected';
                            }
                        ?>>Samsung</option>
                        <option value="volvo" <?php
                            if($invoice->getOrganization() === 'volvo'){
                                echo ' selected';
                            }
                        ?>>Volvo</option>
                        <option value="nestle"  <?php
                            if($invoice->getOrganization() === 'nestle'){
                                echo ' selected';
                            }
                        ?>>Nestle</option>
                        <option value="gsp"  <?php
                            if($invoice->getOrganization() === 'gsp'){
                                echo ' selected';
                            }
                        ?>>GSP</option>
                    </select> <br>
        <button type="submit" name="save">Sačuvaj</button>
        <button type="reset" name="reset">Očisti</button>
    </form>
    <br>
    <table>
        <tr>
            <th>Redni broj</th>
            <th>Naziv artikla</th>
            <th>Količina</th>
        </tr>
        <?php for($i=1, $iMax = count($invoice->getItems()); $i< $iMax; $i++) { ?>
        <tr>
            <td><?php
                echo $i;
                ?></td>
            <td><?php
                echo $invoice->getItems()[$i]->getItemName();
                ?></td>
            <td><?php
                echo $invoice->getItems()[$i]->getQuantity();
                ?></td>
        </tr>
        <?php  }?>
    </table>


    <form action="" method="post" name="stavka">
        <label for="itemName"> Naziv artikla: </label> <input type="text" name="itemName"> <br>
        <label for="quantity"> Količina </label> <input type="number" name="quantity"> <br>
        <button type="submit" name="saveItem">Sačuvaj stavku</button>
    </form>

</body>
</html>

