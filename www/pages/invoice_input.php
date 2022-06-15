
<!doctype html>
<html lang="en" style="height: 100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/style.css"/>
    <title>Faktura | Unos</title>
    <link rel="icon" type="image/x-icon" href="styles/images/fileIcon.png"
    ">
</head>
<body>

<div class="links">
    <a href="pages/invoiceSearch.php">Pretraga fakture</a>
</div>
<div id="invoiceForm">

    <label class="title"> Unos nove fakture </label>
    <div id="invoiceInput">
        <form method="post" action="" name="invoiceF" id="invoiceF">
            <div class="input_group">
                <label>Broj računa: </label>
                <input type="number" name="invoiceNumber" id="invoiceNumber" class="input_invoice_number" value="<?php
                if (!isset($_SESSION['invoiceNumber'])) {
                    echo "";
                } else {
                    echo $_SESSION['invoiceNumber'];
                }
                ?>" placeholder=""><br>
            </div>
            <div class="input_group">
                <label>Datum: </label>
                <input type="date" name="date" id="date" value="<?php
                if (!isset($_SESSION['date'])) {
                    echo "";
                } else {
                    echo $_SESSION['date'];
                }
                ?>"><br>
            </div>
            <div class="input_group">
                <label>Organizacija: </label>
                <?php $brands = getBrands();
                ?>
                <select name="organization" id="organization">
                    <?php foreach ($brands as $key=>$value){?>
                        <option value=<?php echo $key?> <?php
                        if (isset($_SESSION['organization']) && $_SESSION['organization'] === $key) {
                            echo ' selected';
                        }
                        ?>><?php echo $value?></option>
                    <?php }?>
                </select>
            </div>
            <br>

            <button type="submit" name="add" value="add">Dodaj</button>
        </form>
        <br><br>
        <div class="save_invoice">
            <button type="submit" name="save" value="save" class="save_invoice_button" form="invoiceF">Sačuvaj fakturu
            </button>
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
                        <button type="submit" form="itemF" name="removeItemBtn"
                                value="<?php echo $item->getItemName() ?>">
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