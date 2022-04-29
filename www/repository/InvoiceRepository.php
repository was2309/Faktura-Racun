<?php

interface InvoiceRepository
{
    public function save(Invoice $invoice):void;
    public function findById(int $invoiceNumber):Invoice;
    public function update(Invoice $invoice):Invoice;
    public function delete(Invoice $invoice):void;
    public function checkIfExists(int $invoiceNumber):bool;
}