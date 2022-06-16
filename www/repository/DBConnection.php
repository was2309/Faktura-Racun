<?php

include_once 'DotEnv.php';
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
        (new DotEnv(__DIR__.'/db.env'))->load();
        $servername = getenv('SERVERNAME');
        $username = getenv('USERNAME');
        $password = getenv('PASSWORD');
        $dbname = getenv('DB_NAME');

        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);

        }else{
//             ConsoleLog::console_log("Connection is successful!", true);
        }
        return $conn;

    }

}

?>