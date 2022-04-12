<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "invoices";

    //phpinfo();

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo "Connection is successfull!";
    }


    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo "Connection is successfull! ";
    }

    $query_invoice = "CREATE TABLE invoice(
                        invoice_number int(11) NOT NULL PRIMARY KEY,
                        date DATE,
                        organization varchar(255)
                        );";

    if($conn->query($query_invoice) === TRUE){
        echo "Table 'invoice' is created successfully! ";
    }else{
        echo "Error creating table 'invoice': " . $conn->error . " ";
    }

    $query_item = "CREATE TABLE invoice_item(
                            invoice_number int(11) NOT NULL,
                            item_id int(11) NOT NULL,
                            item_name varchar(255) NOT NULL,
                            quantity int,
                            CONSTRAINT PK_ITEM PRIMARY KEY (invoice_number, item_id),
                            FOREIGN KEY (invoice_number) REFERENCES invoice(invoice_number) 
                            );";

    if($conn->query($query_item) === TRUE){
        echo "Table 'invoice_item' is created successfully! ";
    }else{
        echo "Error creating table 'invoice_items': " . $conn->error . " ";
    }


    $conn->close();

?>