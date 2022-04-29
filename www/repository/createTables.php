<?php
    include_once '../styles/console_log.php';
    $servername = "database";
    $username = "root";
    $password = "root";
    $dbname = "invoices";


    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo console_log("Connection is successfull!", true);
    }


//    $conn = new mysqli($servername, $username, $password, $dbname);
//
//    if($conn->connect_error){
//        die("Connection failed: " . $conn->connect_error);
//    }else{
//        echo "Connection is successfull! ";
//    }

    $query_invoice = "CREATE TABLE invoice(
                        invoice_id int(11) NOT NULL AUTO_INCREMENT,
                        invoice_number int(11) NOT NULL,
                        date DATE,
                        organization varchar(255),
                        PRIMARY KEY (invoice_id),
                        CONSTRAINT UI UNIQUE (invoice_number)
                        );";

    if($conn->query($query_invoice) === TRUE){
        echo console_log("Table 'invoice' is created successfully! ", true);
    }else{
        echo console_log("Error creating table 'invoice': " . $conn->error . " ", true);
    }

    $query_item = "CREATE TABLE invoice_item(
                            item_id int(11) NOT NULL AUTO_INCREMENT,
                            invoice_id int(11) NOT NULL,
                            item_name varchar(255),
                            quantity int,
                            PRIMARY KEY (item_id),
                            FOREIGN KEY (invoice_id) REFERENCES invoice(invoice_id),
                            CONSTRAINT UII UNIQUE (item_id, invoice_id)
                            );";

    if($conn->query($query_item) === TRUE){
        echo console_log("Table 'invoice_item' is created successfully! ", true);
    }else{
        echo console_log("Error creating table 'invoice_items': " . $conn->error . " ", true);
    }


    $conn->close();

?>