<?php


    include_once 'DotEnv.php';

    (new DotEnv(__DIR__.'/db.env'))->load();
    $servername = getenv('SERVERNAME');
    $username = getenv('USERNAME');
    $password = getenv('PASSWORD');


    $conn = new mysqli($servername, $username, $password);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }else{
        // ConsoleLog::console_log("Connection is successful!", true);
    }

    $sql_create_db = "CREATE DATABASE invoices";
    if($conn->query($sql_create_db) === TRUE){
//         ConsoleLog::console_log("Database created successfully with name 'invoices' ", true);
    }else{
//         ConsoleLog::console_log("Error creating database: " . $conn->error . " ", true);
    }

    $conn->close();

?>


