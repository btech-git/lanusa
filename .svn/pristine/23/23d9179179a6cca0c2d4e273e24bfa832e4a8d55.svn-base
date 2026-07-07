<?php

class Receive extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $receiveHeader = ReceiveHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
                ));

        if ($receiveHeader !== null)
            $this->header->setCodeNumber($receiveHeader->cn_ordinal, $receiveHeader->cn_month, $receiveHeader->cn_year, $receiveHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $sql = "SELECT p.id, p.product_id, p.quantity - SUM(COALESCE(r.quantity, 0)) AS quantity_ordered
                FROM " . PurchaseDetail::model()->tableName() . " p
                LEFT OUTER JOIN " . ReceiveDetail::model()->tableName() . " r ON p.id = r.purchase_detail_id AND r.is_inactive = 0
                WHERE p.purchase_header_id = :purchase_header_id AND p.is_inactive = 0
                GROUP BY p.id
                HAVING quantity_ordered > 0";

        $resultSet = CActiveRecord::$db->createCommand($sql)->queryAll(true, array(':purchase_header_id' => $id));
        $this->details = array();

        foreach ($resultSet as $row) {
            $detail = new ReceiveDetail();
            $detail->product_id = $row['product_id'];
            $detail->purchase_detail_id = $row['id'];
            $this->details[] = $detail;
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
            $this->header->addError('error', $e->getMessage());
        }

        return $valid;
    }

    public function validate() {
        $valid = $this->header->validate();
        if (!$valid)
            $this->header->addError('error', 'Header Error');

        $valid = $this->validateDetailsCount() && $valid;
        if (!$valid)
            $this->header->addError('error', 'Details Count Error');

        if (count($this->details) > 0) {
            foreach ($this->details as $detail) {
                $fields = array('quantity', 'product_id', 'warehouse_id', 'purchase_detail_id');
                $valid = $detail->validate($fields) && $valid;
                if (!$valid)
                    $this->header->addError('error', 'Details Error');
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
        Inventory::model()->deleteAllByAttributes(array(
            'transaction_ordinal' => $this->header->cn_ordinal,
            'transaction_month' => $this->header->cn_month,
            'transaction_year' => $this->header->cn_year,
            'branch_id' => $this->header->branch_id,
            'transaction_type' => 1,
        ));

        $valid = $this->header->save(false);
        foreach ($this->details as $detail) {
            if ($detail->quantity <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->receive_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;

            $transactionSubject = $this->header->purchaseHeader(array(
                        'scopes' => 'resetScope',
                        'with' => 'supplier:resetScope',
                    ))->supplier->company;

            if ($detail->is_inactive == 0) {
                $inventory = new Inventory();
                $inventory->transaction_ordinal = $this->header->cn_ordinal;
                $inventory->transaction_month = $this->header->cn_month;
                $inventory->transaction_year = $this->header->cn_year;
                $inventory->transaction_type = 1;
                $inventory->transaction_subject = $transactionSubject;
                $inventory->product_id = $detail->product_id;
                $inventory->admin_id = $this->header->admin_id;
                $inventory->branch_id = $this->header->branch_id;
                $inventory->date = $this->header->date;
                $inventory->quantity_in = $detail->quantity;
                $inventory->price = $detail->product->costOfGoodsSold;
                $inventory->warehouse_id = $detail->warehouse_id;

                $valid = $inventory->save(false) && $valid;
                if (!$valid)
                    $this->header->addError('error', 'Inventory Error');
            }
        }

        return $valid;
    }

    public function getAmount() {
        $amount = 0.00;
        foreach ($this->details as $detail) {
            $purchaseDetail = PurchaseDetail::model()->findByAttributes(array('purchase_header_id' => $this->header->purchase_header_id, 'product_id' => $detail->product_id));
            $unitPrice = ($purchaseDetail === null) ? 0.00 : $purchaseDetail->unit_price * (1 - $purchaseDetail->discount / 100) * (1 - $purchaseDetail->purchaseHeader->discount / 100);
            $amount = $detail->quantity * $unitPrice;
        }

        return $amount;
    }

}
