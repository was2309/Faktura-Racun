<?php

    include_once '../styles/ConsoleLog.php';
    $servername = "database";
    $username = "root";
    $password = "root";

    $conn = new mysqli($servername, $username, $password);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo ConsoleLog::console_log("Connection is successfull!", true);
    }

    $sql_create_db = "CREATE DATABASE invoices";
    if($conn->query($sql_create_db) === TRUE){
        echo ConsoleLog::console_log("Database created successfully with name 'invoices' ", true);
    }else{
        echo ConsoleLog::console_log("Error creating database: " . $conn->error . " ", true);
    }

    $conn->close();

?>


