<?php
    include_once '../styles/ConsoleLog.php';
//    include_once 'DBConnection.php';
    include_once 'DotEnv.php';

    (new DotEnv(__DIR__.'/db.env'))->load();
    $servername = getenv('SERVERNAME');
    $username = getenv('USERNAME');
    $password = getenv('PASSWORD');
    $dbname = getenv('DB_NAME');


    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
//         ConsoleLog::console_log("Connection is successfull!", true);
    }

//    $conn = DBConnection::getInstance()->connect();


    $query_invoice = "CREATE TABLE invoice(
                        invoice_id int(11) NOT NULL AUTO_INCREMENT,
                        invoice_number int(11) NOT NULL,
                        date DATE,
                        organization varchar(255),
                        PRIMARY KEY (invoice_id),
                        CONSTRAINT UI UNIQUE (invoice_number)
                        );";

    if($conn->query($query_invoice) === TRUE){
//         ConsoleLog::console_log("Table 'invoice' is created successfully! ", true);
    }else{
//         ConsoleLog::console_log("Error creating table 'invoice': " . $conn->error . " ", true);
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
//         ConsoleLog::console_log("Table 'invoice_item' is created successfully! ", true);
    }else{
//         ConsoleLog::console_log("Error creating table 'invoice_items': " . $conn->error . " ", true);
    }


    $conn->close();

?>