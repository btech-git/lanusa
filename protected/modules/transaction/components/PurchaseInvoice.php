<?php

class PurchaseInvoice extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $purchaseInvoice = PurchaseInvoiceHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear),
        ));

        if ($purchaseInvoice !== null)
            $this->header->setCodeNumber($purchaseInvoice->cn_ordinal, $purchaseInvoice->cn_month, $purchaseInvoice->cn_year, $purchaseInvoice->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $purchaseHeader = PurchaseHeader::model()->findByPk($id);

        if ($purchaseHeader !== null) {
            $exist = false;
            foreach ($this->details as $i => $detail) {
                if ($purchaseHeader->id === $detail->purchase_header_id) {
                    $exist = true;
                    break;
                }
            }
            if ($purchaseHeader->supplier_id !== $this->header->supplier_id)
                $exist = true;

            if (!$exist) {
                $detail = new PurchaseInvoiceDetail();
                $detail->purchase_header_id = $purchaseHeader->id;
                $this->details[] = $detail;
            }
        }
    }

    public function resetDetail() {
        $this->details = array();
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
    }

    public function validate() {
        $valid = $this->header->validate();

        $valid = $this->validateDetailsCount() && $valid;

        if (count($this->details) > 0) {
            foreach ($this->details as $detail) {
                $fields = array('memo', 'purchase_invoice_id');
                $valid = $detail->validate($fields) && $valid;
            }
        } else
            $valid = false;

        return $valid;
    }

    public function validateDetailsCount() {
        $valid = true;

        if (count($this->details) === 0) {
            $valid = false;
            $this->header->addError('error', 'Form tidak ada data untuk insert database. Minimal satu data detail untuk melakukan penyimpanan.');
        }

        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);
        foreach ($this->details as $detail) {
            if ($detail->isNewRecord)
                $detail->purchase_invoice_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;
        }

        return $valid;
    }

    public function save($dbConnection) {
        $dbTransaction = $dbConnection->beginTransaction();
        try {
            $valid = $this->validate() && IdempotentManager::build()->save() && $this->flush();
            if ($valid)
                $dbTransaction->commit();
            else
                $dbTransaction->rollback();
        } catch (Exception $e) {
            $dbTransaction->rollback();
            $valid = false;
        }

        return $valid;
    }

    public function getTotalPurchase() {
        $total = 0.00;

        foreach ($this->details as $detail)
            $total += $detail->purchaseHeader->grandTotal;

        return $total;
    }
}
