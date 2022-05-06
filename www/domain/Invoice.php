<?php

class Invoice
{
    private $invoiceId;
    private $invoiceNumber;
    private $date;
    private $organization;

    /**
     * @var InvoiceItem[]
     */
    private array $items = array();

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * @param mixed $invoiceId
     */
    public function setInvoiceId($invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber($invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }


    public function getDate()
    {
        return $this->date;
    }


    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getOrganization(){
        return $this->organization;
    }

    public function setOrganization($organization):void
    {
        $this->organization = $organization;
    }


    public function getItems():array
    {
        return $this->items;
    }


    public function setItems($items): void
    {
        $this->items = $items;
    }

    public function addNewItem(InvoiceItem $item):void{
        if($item->getItemName() === null || $item->getQuantity() === null){
            return;
        }
        foreach ($this->items as $i){
            if($i == $item) {
                return;
            }
            if($i->getItemName() === $item->getItemName() && $i->getQuantity()!== $item->getQuantity()){
                $i->setQuantity($i->getQuantity()+$item->getQuantity());
                return;
            }
        }
        $this->items[] = $item;
    }

    public function removeItem(InvoiceItem $item):void{
        if($item->getItemName() === null || $item->getQuantity() === null){
            return;
        }
        $count = 0;
        foreach ($this->items as $i){
            if($item->getItemName() === $i->getItemName()){
                unset($this->items[$count]);
                $this->items = array_values($this->items);
                return;
            }
            $count++;
        }

    }

}