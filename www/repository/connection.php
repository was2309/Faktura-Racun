<?php

    include_once '../styles/console_log.php';
    $servername = "database";
    $username = "root";
    $password = "root";
    $dbname = "invoices";

    //phpinfo();

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo console_log("Connection is successfull!", true);
    }


?>