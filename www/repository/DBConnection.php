<?php
include_once '../styles/ConsoleLog.php';
class DBConnection{

    public static ?DBConnection $instance = null;

    private function __construct()
    {

    }

    public static function getInstance(): DBConnection{
        if(self::$instance === null){
            self::$instance = new DBConnection();
        }
        return self::$instance;
    }

    public function connect(){
        $servername = "database";
        $username = "root";
        $password = "root";
        $dbname = "invoices";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);

        }else{
            echo ConsoleLog::console_log("Connection is successfull!", true);
        }
        return $conn;

    }

}

?>