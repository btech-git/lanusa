<?php

class FeeInvoice extends FeeInvoiceBase {
    const CN_CONSTANT = 'FIN';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $feeInvoice = FeeInvoice::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($feeInvoice !== null) {
            $this->setCodeNumber($feeInvoice->cn_ordinal, $feeInvoice->cn_month, $feeInvoice->cn_year, $feeInvoice->branch_id);
        }

        $this->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function getTaxItemAmount() {
        return $this->fee_amount * $this->tax_item_value / 100;
    }
    
    public function getTaxServiceValue() {
        return $this->fee_amount * $this->tax_service_value / 100;
    }
}
