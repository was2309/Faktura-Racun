<?php
require_once __DIR__ . '/../services/InvoiceService.php';
require_once __DIR__ . '/../services/impl/InvoiceServiceImplementation.php';
require_once __DIR__ . '/../dto/DTOItem.php';
require_once __DIR__ . '/../dto/DTOInvoice.php';

class InvoiceController
{
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->invoiceService = new InvoiceServiceImplementation();
    }

    public function save(DTOInvoice $DTOInvoice): void
    {
        if ($DTOInvoice === null) {
            echo "Molimo unesite ispravnu fakturu!";
            return;
        }
        if ($DTOInvoice->getInvoiceNumber() === null || $DTOInvoice->getInvoiceNumber() < 1) {
            echo "Molimo unesite ispravan broj fakture!";
            return;
        }
        if ($DTOInvoice->getDate() === null) {
            echo "Molimo unesite ispravan datum fakture!";
            return;
        }
        if (empty($DTOInvoice->getOrganization())) {
            echo "Molimo izaberite organizaciju!";
            return;
        }
        if ($DTOInvoice->getItems() !== null) {
            foreach ($DTOInvoice->getItems() as $item) {
                if (empty($item->getItemName())) {
                    echo "Molimo unesite naziv stavke, kako bi ona mogla biti sacuvana!";
                    return;
                }
                if (empty($item->getQuantity())) {
                    echo "Molimo unesite kolicinu stavke, kako bi ona mogla biti sacuvana!";
                    return;
                }
            }
        }

        $this->invoiceService->save($DTOInvoice);

    }

    public function findById(int $invoice_number, int $b): ?DTOInvoice
    {
        if ($invoice_number < 1) {
            echo "Molimo unesite ispravan broj fakture koju pretražujete! ";
            return null;
        }

        $DTOInvoice = $this->invoiceService->findById($invoice_number);

        if ($DTOInvoice === null) {
//            echo "Nije pronađena faktura sa unesenim brojem!";
//            return null;
            include_once __DIR__ . '/../pages/invoice_input.php';
            //throw new \mysql_xdevapi\Exception("Nije pronadjena faktura sa unetim brojem");
        }

        include_once __DIR__ . '/../pages/invoiceSearch.php';
    }

    public function update(DTOInvoice $DTOInvoice): DTOInvoice
    {
        $DTOInv = new DTOInvoice();
        if (!isset($DTOInvoice)) {
            echo "Molimo unesite fakturu koju hoćete da izmenite! ";
            return $DTOInv;
        }
        if ($DTOInvoice->getInvoiceNumber() === null || $DTOInvoice->getInvoiceNumber() < 1) {
            echo "Molimo unesite ispravan broj fakture!";
            return $DTOInv;
        }
        if ($DTOInvoice->getDate() === null) {
            echo "Molimo unesite ispravan datum fakture!";
            return $DTOInv;
        }
        if (empty($DTOInvoice->getOrganization())) {
            echo "Molimo izaberite organizaciju!";
            return $DTOInv;
        }
        if ($DTOInvoice->getItems() !== null) {
            foreach ($DTOInvoice->getItems() as $item) {
                if (empty($item->getItemName())) {
                    echo "Molimo unesite naziv stavke, kako bi ona mogla biti sacuvana!";
                    return $DTOInv;
                }
                if (empty($item->getQuantity())) {
                    echo "Molimo unesite kolicinu stavke, kako bi ona mogla biti sacuvana!";
                    return $DTOInv;
                }
            }
        }
        $invoiceNum = $DTOInvoice->getInvoiceNumber();
        $DTOInv = $this->invoiceService->update($invoiceNum, $DTOInvoice);

        if ($DTOInv->getInvoiceId() === null) {
            echo "Faktura nije ažurirana! ";
        } else {
            echo "Faktura je uspešno ažurirana! ";
        }
        return $DTOInv;

    }

    public function delete(DTOInvoice $DTOInvoice): void
    {
        $invoiceID = $DTOInvoice->getInvoiceId();
        $invoiceNumber = $DTOInvoice->getInvoiceNumber();
        if ($invoiceID < 1 || $invoiceNumber < 1) {
            echo "Molimo unesite ispravan broj fakute koju želite da obrišete! ";
            return;
        }

        $this->invoiceService->delete($invoiceID, $invoiceNumber);
    }

}