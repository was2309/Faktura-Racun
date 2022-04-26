<?php

class Controller
{
    public static Controller $instance;

    private function __construct(){

    }

    public static function getInstance() :Controller{
        if(self::$instance === null){
            self::$instance = new Controller();
        }
        return self::$instance;
    }

    public function addInvoice(int $invoiceNumber, $date, string $organization){
        if($invoiceNumber === null || $invoiceNumber < 1){
            echo "Molimo unesite ispravan broj fakture!";
            return;
        }
        if($date === null || empty($date)){
            echo "Molimo unesite datum fakture!";
            return;
        }
        if($organization === null || empty($organization)){
            echo "Molimo izaberite organizaciju!";
            return;
        }




    }



}