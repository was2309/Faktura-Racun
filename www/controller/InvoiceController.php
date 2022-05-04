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

    public function findById(int $invoice_number): DTOInvoice{
        $DTOInvoice = new DTOInvoice();
        if($invoice_number < 1){
            echo "Molimo unesite ispravan broj fakture koju pretražujete! ";
            $DTOInvoice = null;
            return $DTOInvoice;
        }
        $DTOInvoice = $this->invoiceService->findById($invoice_number);
        if($DTOInvoice === null){
            echo "Nije pronađena faktura sa unesenim brojem!";
        }
        return $DTOInvoice;
    }







}