<?php

class Purchase extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $purchaseHeader = PurchaseHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
                ));

        if ($purchaseHeader !== null)
            $this->header->setCodeNumber($purchaseHeader->cn_ordinal, $purchaseHeader->cn_month, $purchaseHeader->cn_year, $purchaseHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $product = Product::model()->findByPk($id);

        if ($product !== null) {
            $exist = false;
            foreach ($this->details as $i => $detail) {
                if ($product->id === $detail->product_id) {
                    $exist = true;
                    break;
                }
            }

            if ($exist)
                $this->details[$i]->quantity++;
            else {
                $detail = new PurchaseDetail();
                $detail->product_id = $product->id;
                $this->details[] = $detail;
            }
        }
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
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

    public function validate() {
        $valid = $this->header->validate();

        $valid = $this->validateDetailsCount() && $valid;

        if (count($this->details) > 0) {
            foreach ($this->details as $detail) {
                $fields = array('quantity', 'unit_price');
                $valid = $detail->validate($fields) && $valid;
            }
        }
        else
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
//        $this->header->tax = $this->getTaxPercentage();
        $valid = $this->header->save(false);

        foreach ($this->details as $detail) {
            if ($detail->quantity <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->purchase_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;
        }

        return $valid;
    }

    public function getSubTotal() {
        $total = 0.00;
        
        foreach ($this->details as $detail) {
            $total += $detail->total;
        }
        
        return ((int)$this->header->is_non_tax == PurchaseHeader::INCLUDE_TAX) ? $total / (1 + ($this->header->tax / 100)) : $total;
    }

    public function getTaxPercentage() {
        
        return empty($this->header->branch_id) ? 0 : ((int)$this->header->branch->is_tax == 0) ? 0 : 10;
    }

    public function getCalculatedTax() {
        return ($this->subTotal - $this->header->discount) * ($this->header->tax / 100);
    }

    public function getGrandTotal() {
        return $this->subTotal - $this->header->discount + $this->calculatedTax + $this->header->shipping_fee;
    }

}
