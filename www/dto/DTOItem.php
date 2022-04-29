<?php

class DTOItem
{
    private int $invoiceNumber;
    private string $itemName;
    private int $quantity;

    private bool $isNew;
    private bool $forDelete;

    public function __construct()
    {
        $this->forDelete = false;
    }

    /**
     * @return int
     */
    public function getInvoiceNumber(): int
    {
        return $this->invoiceNumber;
    }

    /**
     * @param int $invoiceNumber
     */
    public function setInvoiceNumber(int $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return string
     */
    public function getItemName(): string
    {
        return $this->itemName;
    }

    /**
     * @param string $itemName
     */
    public function setItemName(string $itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
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