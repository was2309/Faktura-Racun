<?php

class InvoiceItem
{
    private $invoiceNumber;
    private $itemID;
    private $itemName;
    private $quantity;


    public function __construct($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
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




    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }


    public function setInvoiceNumber($invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }


    public function getItemID()
    {
        return $this->itemID;
    }


    public function setItemID($itemID): void
    {
        $this->itemID = $itemID;
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