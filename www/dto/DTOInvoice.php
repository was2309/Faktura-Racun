<?php
include_once 'DTOItem.php';
class DTOInvoice
{
    private int $invoiceNumber;
    private $date;
    private string $organization;

    /**
     * @var DTOItem[]
     */
    private array $items = array();


    public function __construct()
    {

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
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getOrganization(): string
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     */
    public function setOrganization(string $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return DTOItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param DTOItem[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function addItem(DTOItem $item){
        if($item->getItemName() === null || $item->getQuantity() === null){
            return;
        }
        foreach ($this->items as $i){
            if($i == $item) {
//               echo '<script>alert("Stavka vec postoji!")</script>';
                return;
            }
            if($i->getItemName() === $item->getItemName() && $i->getQuantity()!== $item->getQuantity()){
                $i->setQuantity($i->getQuantity()+$item->getQuantity());
                return;
            }
        }
        $this->items[] = $item;
    }

    public function removeItem(DTOItem $item){
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