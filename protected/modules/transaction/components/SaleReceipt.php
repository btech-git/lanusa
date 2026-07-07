<?php

class SaleReceipt extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $saleReceiptHeader = SaleReceiptHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($saleReceiptHeader !== null) {
            $this->header->setCodeNumber($saleReceiptHeader->cn_ordinal, $saleReceiptHeader->cn_month, $saleReceiptHeader->cn_year, $saleReceiptHeader->branch_id);
        }
        
        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $saleInvoice = SaleInvoice::model()->findByPk($id);

        if ($saleInvoice !== null) {
            $exist = false;
            foreach ($this->details as $i => $detail) {
                if ($saleInvoice->id === $detail->sale_invoice_id) {
                    $exist = true;
                    break;
                }
            }
            
            if (!$exist) {
                $detail = new SaleReceiptDetail();
                $detail->sale_invoice_id = $saleInvoice->id;
                $this->details[] = $detail;
            }
        }
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
    }

    public function resetDetail() {
        $this->details = array();
    }

    public function save($dbConnection) {
        $dbTransaction = $dbConnection->beginTransaction();
        try {
            $valid = $this->validate() && IdempotentManager::build()->save() && $this->flush();
            if ($valid) {
                $dbTransaction->commit();
            } else {
                $dbTransaction->rollback();
            }
        } catch (Exception $e) {
            $dbTransaction->rollback();
            $valid = false;
        }

        return $valid;
    }

    public function validate() {
        $valid = $this->header->validate();

        if ($this->header->isNewRecord) {
            $valid = $this->validateDetailsCount() && $valid;
        }

        foreach ($this->details as $detail) {
            if ($detail->is_inactive === 0) {
                $fields = array('memo', 'sale_invoice_id');
                $valid = $detail->validate($fields) && $valid;
            }
        }

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
        $this->header->grand_total = $this->getTotalInvoice();
        $valid = $this->header->save(false);

        foreach ($this->details as $detail) {
            if ($detail->isNewRecord) {
                $detail->sale_receipt_header_id = $this->header->id;
            }
            
            $valid = $detail->save(false) && $valid;
        }

        return $valid;
    }

    public function getTotalInvoice() {
        $total = 0.00;

        foreach ($this->details as $detail) {
            if ((int) $detail->is_inactive === 0) {
                $total += $detail->saleInvoice->grandTotal;
            }
        }

        return $total;
    }
}
?>