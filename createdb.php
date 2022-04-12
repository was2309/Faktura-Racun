<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    //phpinfo();

    $conn = new mysqli($servername, $username, $password);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo "Connection is successfull!";
    }

    $sql_create_db = "CREATE DATABASE invoices";
    if($conn->query($sql_create_db) === TRUE){
        echo "Database created successfully with name 'invoices' ";
    }else{
        echo "Error creating database: " . $conn->error;
    }

    $test_query_invoice = "SELECT invoice_number FROM invoice";
    $test_result_invoice = mysqli_query($conn, $test_query_invoice);

    if(empty($test_result_invoice)){
        $query_invoice = "CREATE TABLE invoice(
                    invoice_number int(11) NOT NULL PRIMARY KEY,
                    date DATE,
                    organization varchar(255)
                    );";

        $result_invoice = mysqli_query($conn, $query_invoice);
    }else{
        echo "Table 'invoice' already exists!";
    }

    $test_query_item = "SELECT invoice_number FROM invoice_item";
    $test_result_item = mysqli_query($conn, $test_query_item);

    if(empty($test_result_item)){
        $query_item = "CREATE TABLE invoice_item(
                        invoice_number int(11) NOT NULL PRIMARY KEY,
                        item_name varchar(255) NOT NULL,
                        quantity int(11),
                        FOREIGN KEY (invoice_number) REFERENCES invoice(invoice_number) 
                        );";
    }else{
        echo "Table 'invoice_item' already exists!";
    }


    $conn->close();

?>


