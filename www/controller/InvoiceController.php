<?php
include_once '../services/InvoiceService.php';
include_once '../services/impl/InvoiceServiceImplementation.php';
class InvoiceController
{
    public static InvoiceController $instance;

    private $invoiceService;

    public function __construct(){
        $this->invoiceService = new InvoiceServiceImplementation();
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

        $this->invoiceService->addInvoice($invoiceNumber,$date,$organization);
    }

    public function createItem(int $invoiceNumber, string $itemName, int $quantity){
        if($invoiceNumber === null || $invoiceNumber < 1){
            echo "Molimo unesite ispravan broj fakture!";
            return;
        }
        if($itemName === null || empty($itemName)){
            echo "Molimo unesite naziv stavke!";
            return;
        }
        if($quantity === null || $quantity<1){
            echo "Molimo unesite odgovarajucu kolicinu stavke!";
            return;
        }
        $this->invoiceService->createItem($invoiceNumber,$itemName, $quantity);
    }





}