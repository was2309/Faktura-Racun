<?php

interface InvoiceService
{
    public function save(int $invoiceNumber, $date, string $organization):void;
    public function findById(int $invoiceNumber):Invoice;
    public function update(int $invoiceNumber, Invoice $invoice):Invoice;
    public function delete(int $invoiceNumber):void;
    public function addInvoice(int $invoiceNumber, $date, string $organization):void;
    public function createItem(int $invoiceNumber, string $itemName, int $quantity):void;
    public function removeItem(int $invoiceNumber, int $itemId):void;

}