<?php

class InvoiceItem
{
    private $invoice;
    private $itemName;
    private $quantity;


    public function __construct($faktura)
    {
        $this->invoice = $faktura;
    }


    public function getInvoice()
    {
        return $this->invoice;
    }


    public function setInvoice($invoice): void
    {
        $this->invoice = $invoice;
    }


    public function getItemName()
    {
        return $this->itemName;
    }


    public function setItemName($itemName): void
    {
        $this->itemName = $itemName;
    }


    public function getQuantity()
    {
        return $this->quantity;
    }


    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }




}