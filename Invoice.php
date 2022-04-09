<?php

class Invoice
{
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

//    public function __serialize(): array
//    {
//        return [
//          'invoiceNumber'=> $this->invoiceNumber,
//          'date'=>$this->date,
//          'organization'=>$this->organization
//        ];
//    }
//
//
//    public function __unserialize(array $data): void
//    {
//        $this->invoiceNumber = $data['invoiceNumber'];
//        $this->date = $data['date'];
//        $this->organization = $data['organization'];
//    }

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

    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }


    public function getItems()
    {
        return $this->items;
    }


    public function setItems($items): void
    {
        $this->items = $items;
    }

    public function addNewItem($item){
        $this->items[] = $item;
    }



}