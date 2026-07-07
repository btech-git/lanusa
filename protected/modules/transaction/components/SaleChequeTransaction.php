<?php

class SaleChequeTransaction extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $saleChequeHeader = SaleChequeHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($saleChequeHeader !== null)
            $this->header->setCodeNumber($saleChequeHeader->cn_ordinal, $saleChequeHeader->cn_month, $saleChequeHeader->cn_year, $saleChequeHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $saleReceiptHeader = SaleReceiptHeader::model()->findByPk($id);

        if ($saleReceiptHeader !== null) {
            $exist = false;
            foreach ($this->details as $i => $detail) {
                if ($saleReceiptHeader->id === $detail->sale_receipt_header_id) {
                    $exist = true;
                    break;
                }
            }

            if (!$exist) {
                $detail = new SaleChequeDetail();
                $detail->sale_receipt_header_id = $saleReceiptHeader->id;
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

    public function getTotalAmount() {           //total amount for ajax json amount since the header has no details yet
        $total = 0;
        foreach ($this->details as $detail) {
            $total += $detail->amount;
        }

        return $total;
    }

    public function getTotalSaleReceipt() {
        $total = 0;
        foreach ($this->details as $detail) {
            $total += $detail->saleReceiptHeader->getTotalInvoice();
        }

        return $total;
    }

    public function validateDetailsCount() {
        $valid = true;
        if (count($this->details) === 0) {
            $valid = false;
        }

        return $valid;
    }

    public function validate() {
        $valid = $this->header->validate();
        if (!$valid)
            $this->header->addError('error', 'Header Error');
        if ($valid) {
            $valid = $this->validateDetailsCount() && $valid;
            if (!$valid)
                $this->header->addError('error', 'Minimal satu data detail untuk melakukan penyimpanan.');

            if ($valid) {
                foreach ($this->details as $detail) {
                    $fields = array('bank', 'cheque_number', 'amount', 'sale_receipt_header_id');
                    $valid = $detail->validate($fields) && $valid;
                    if (!$valid)
                        $this->header->addError('error', 'Detail Error');
                }
            } else
                $valid = false;
        }


        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);

        foreach ($this->details as $detail) {
            if ($detail->isNewRecord)
                $detail->sale_cheque_header_id = $this->header->id;

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
            $this->header->addError('error', 'Exception Error');
        }

        return $valid;
    }
}
