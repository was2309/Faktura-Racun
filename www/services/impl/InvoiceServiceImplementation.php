<?php
include_once __DIR__ .  '/../../domain/Invoice.php';
include_once __DIR__ .  '/../../domain/InvoiceItem.php';
include_once __DIR__ .  '/../../repository/InvoiceRepositoryMySQLImpl.php';
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
       // return $this->invoiceRepository->findById($invoiceNumber);
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