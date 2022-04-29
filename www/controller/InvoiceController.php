<?php
include_once '../services/InvoiceService.php';
include_once '../services/impl/InvoiceServiceImplementation.php';
include_once '../dto/DTOItem.php';
include_once '../dto/DTOInvoice.php';
class InvoiceController
{
    private $invoiceService;

    public function __construct(){
        $this->invoiceService = new InvoiceServiceImplementation();
    }

//    public function addInvoice(int $invoiceNumber, $date, string $organization){
//        if($invoiceNumber === null || $invoiceNumber < 1){
//            echo "Molimo unesite ispravan broj fakture!";
//            return;
//        }
//        if($date === null || empty($date)){
//            echo "Molimo unesite datum fakture!";
//            return;
//        }
//        if($organization === null || empty($organization)){
//            echo "Molimo izaberite organizaciju!";
//            return;
//        }
//
//        $this->invoiceService->addInvoice($invoiceNumber,$date,$organization);
//    }
//
//    public function createItem(int $invoiceNumber, string $itemName, int $quantity){
//        if($invoiceNumber === null || $invoiceNumber < 1){
//            echo "Molimo unesite ispravan broj fakture!";
//            return;
//        }
//        if($itemName === null || empty($itemName)){
//            echo "Molimo unesite naziv stavke!";
//            return;
//        }
//        if($quantity === null || $quantity<1){
//            echo "Molimo unesite odgovarajucu kolicinu stavke!";
//            return;
//        }
//        $this->invoiceService->createItem($invoiceNumber,$itemName, $quantity);
//    }

        public function save(DTOInvoice $DTOInvoice):void{
            if($DTOInvoice === null){
                echo "Molimo unesite ispravnu fakturu!";
                return;
            }
            if($DTOInvoice->getInvoiceNumber() === null || $DTOInvoice->getInvoiceNumber()<1){
                echo "Molimo unesite ispravan broj fakture!";
                return;
            }
            if($DTOInvoice->getDate()===null){
                echo "Molimo unesite ispravan datum fakture!";
                return;
            }
            if(empty($DTOInvoice->getOrganization())){
                echo "Molimo izaberite organizaciju!";
                return;
            }
            if($DTOInvoice->getItems() !== null){
                foreach ($DTOInvoice->getItems() as $item){
                    if(empty($item->getItemName())){
                        echo "Molimo unesite naziv stavke, kako bi ona mogla biti sacuvana!";
                        return;
                    }
                    if(empty($item->getQuantity())){
                        echo "Molimo unesite kolicinu stavke, kako bi ona mogla biti sacuvana!";
                        return;
                    }
                }
            }

            $this->invoiceService->save($DTOInvoice);

        }





}