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
        echo "<script>console.log('Connection is successfull!')</script>";
    }


?>