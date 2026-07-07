<?php

class PurchaseChequeTransaction extends CComponent {

    public $header;

    public function __construct($header) {
        $this->header = $header;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $purchaseCheque = purchaseCheque::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear),
        ));

        if ($purchaseCheque !== null)
            $this->header->setCodeNumber($purchaseCheque->cn_ordinal, $purchaseCheque->cn_month, $purchaseCheque->cn_year, $purchaseCheque->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function validate() {
        $valid = $this->header->validate();

        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);

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
}
