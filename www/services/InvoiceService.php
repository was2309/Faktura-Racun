<?php

interface InvoiceService
{
    public function save(DTOInvoice $DTOInvoice):void;
    public function findById(int $invoiceNumber):?DTOInvoice;
    public function update(int $invoiceNumber, DTOInvoice $DTOInvoice):DTOInvoice;
    public function delete(int $invoiceId, int $invoiceNumber):void;
}