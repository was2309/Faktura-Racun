<?php
include_once '../../domain/Invoice.php';
include_once '../../domain/InvoiceItem.php';
class InvoiceServiceImplementation implements InvoiceService
{

    private static $instance = null;

    private $invoiceRepository;

    public function __construct(){
        $this->invoiceRepository = new InvoiceRepositoryMySQLImpl();
    }

    public function save(int $invoiceNumber, $date, string $organization): void
    {
        // TODO: Implement save() method.
    }

    public function findById(int $invoiceNumber): Invoice
    {
        return $this->invoiceRepository->findById($invoiceNumber);
    }

    public function update(int $invoiceNumber, Invoice $invoice): Invoice
    {
        // TODO: Implement update() method.
    }

    public function delete(int $invoiceNumber): void
    {
        // TODO: Implement delete() method.
    }


    public function addInvoice(int $invoiceNumber, $date, string $organization): void
    {
        $invoice = new Invoice();
        $invoice->setInvoiceNumber($invoiceNumber);
        $invoice->setDate($date);
        $invoice->setOrganization($organization);
    }

    public function createItem(int $invoiceNumber, string $itemName, int $quantity): void
    {
        $invoiceItem = new InvoiceItem($invoiceNumber);
        $invoiceItem->setItemName($itemName);
        $invoiceItem->setQuantity($quantity);
        $invoiceItem->setIsNew(true);
        // TODO: add this item to appropriate invoice
    }

    public function removeItem(int $invoiceNumber, int $itemId): void
    {
        // TODO: Implement removeItem() method.
    }


}