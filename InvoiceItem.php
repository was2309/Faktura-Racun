<?php

class InvoiceItem
{
    private $invoice;
    private $itemName;
    private $quantity;


    public function __construct($invoice)
    {
        $this->invoice=$invoice;
    }




//    public function __serialize(): array
//    {
//        return[
//          'itemName' =>  $this->itemName,
//          'quantity'=> $this->quantity
//        ];
//    }
//
//    public function __unserialize(array $data): void
//    {
//        $this->itemName = $data['itemName'];
//        $this->quantity = $data['quantity'];
//    }


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