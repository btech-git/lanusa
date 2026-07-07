<?php

class SaleDownpaymentTransaction extends CComponent {

    public $header;

    public function __construct($header) {
        $this->header = $header;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $saleDownpayment = saleDownpayment::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($saleDownpayment !== null)
            $this->header->setCodeNumber($saleDownpayment->cn_ordinal, $saleDownpayment->cn_month, $saleDownpayment->cn_year, $saleDownpayment->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function validate() {
        $valid = $this->header->validate();

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

        $yearNow = date('y');

        $taxForm = new TaxForm();
        $taxForm->sale_downpayment_id = $this->header->id;
        $taxForm->admin_id = 1;
        $taxForm->branch_id = 1;
        $taxForm->save($dbConnection);

        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);

        return $valid;
    }

    public function getCalculatedTax1() {
        return $this->amount * $this->tax / 100;
    }

    public function GrandTotal() {
        return $this->amount + $this->calculatedTax;
    }
}
