<?php
include_once __DIR__ .  '/../../domain/Invoice.php';
include_once __DIR__ .  '/../../domain/InvoiceItem.php';
include_once __DIR__ .  '/../../repository/InvoiceRepositoryMySQLImpl.php';
include_once __DIR__ .  '/../../dto/DTOInvoice.php';
include_once __DIR__ .  '/../../dto/DTOItem.php';
class InvoiceServiceImplementation implements InvoiceService
{
    private $invoiceRepository;

    public function __construct(){
        $this->invoiceRepository = new InvoiceRepositoryMySQLImpl();
    }

    public function save(DTOInvoice $DTOInvoice): void
    {
        $invoice = new Invoice();
        $invoice->setInvoiceNumber($DTOInvoice->getInvoiceNumber());
        $invoice->setDate($DTOInvoice->getDate());
        $invoice->setOrganization($DTOInvoice->getOrganization());
        $items = array();
        foreach ($DTOInvoice->getItems() as $item){
            $i = new InvoiceItem();
            $i->setItemName($item->getItemName());
            $i->setQuantity($item->getQuantity());
            $i->setIsNew($item->isNew());
            $i->setForDelete($item->isForDelete());
            $items[]=$i;
        }
        $invoice->setItems($items);

        $this->invoiceRepository->save($invoice);
        session_unset();
    }

    public function findById(int $invoiceNumber): DTOInvoice
    {
       $invoice = $this->invoiceRepository->findById($invoiceNumber);
       $DTOInvoice = new DTOInvoice();
       if($invoice->getInvoiceNumber() !== null){
           $DTOInvoice->setInvoiceId($invoice->getInvoiceId());
           $DTOInvoice->setInvoiceNumber($invoice->getInvoiceNumber());
           $DTOInvoice->setDate($invoice->getDate());
           $DTOInvoice->setOrganization($invoice->getOrganization());
           $items = array();
           foreach ($invoice->getItems() as $item){
                $DTOItem = new DTOItem();
                $DTOItem->setInvoiceId($item->getInvoiceId());
                $DTOItem->setItemName($item->getItemName());
                $DTOItem->setQuantity($item->getQuantity());
                $DTOItem->setIsNew($item->isNew());
                $items[] = $DTOItem;
           }
           $DTOInvoice->setItems($items);

       }

       return $DTOInvoice;
    }

    public function update(int $invoiceNumber, DTOInvoice $DTOInvoice): DTOInvoice
    {
        // TODO: Implement update() method.
    }

    public function delete(int $invoiceNumber): void
    {
        // TODO: Implement delete() method.
    }


//    public function addInvoice(int $invoiceNumber, $date, string $organization): void
//    {
//        $invoice = new Invoice();
//        $invoice->setInvoiceNumber($invoiceNumber);
//        $invoice->setDate($date);
//        $invoice->setOrganization($organization);
//    }
//
//    public function createItem(int $invoiceNumber, string $itemName, int $quantity): void
//    {
//        $invoiceItem = new InvoiceItem($invoiceNumber);
//        $invoiceItem->setItemName($itemName);
//        $invoiceItem->setQuantity($quantity);
//        $invoiceItem->setIsNew(true);
//        // TODO: add this item to appropriate invoice
//    }
//
//    public function removeItem(int $invoiceNumber, int $itemId): void
//    {
//        // TODO: Implement removeItem() method.
//    }


}