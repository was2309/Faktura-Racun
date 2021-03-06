<?php

class InvoiceItem
{
    private $invoiceNumber;
    private $itemID;
    private $itemName;
    private $quantity;
    private bool $isNew;
    private bool $forDelete;


    public function __construct($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->forDelete = false;
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

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew): void
    {
        $this->isNew = $isNew;
    }

    /**
     * @return bool
     */
    public function isForDelete(): bool
    {
        return $this->forDelete;
    }

    /**
     * @param bool $forDelete
     */
    public function setForDelete(bool $forDelete): void
    {
        $this->forDelete = $forDelete;
    }






}