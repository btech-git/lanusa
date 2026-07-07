<?php

class PurchaseReturn extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $purchaseReturnHeader = PurchaseReturnHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($purchaseReturnHeader !== null)
            $this->header->setCodeNumber($purchaseReturnHeader->cn_ordinal, $purchaseReturnHeader->cn_month, $purchaseReturnHeader->cn_year, $purchaseReturnHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addReceive($id) {
        $sql = SqlViewGenerator::quantityReceive() . "
                WHERE receive.id = :receive_id
                HAVING quantity_received > 0";

        $resultSet = CActiveRecord::$db->createCommand($sql)->queryAll(true, array(':receive_id' => $id));

        $this->details = array();

        foreach ($resultSet as $row) {
            $detail = new PurchaseReturnDetail();
            $detail->product_id = $row['product_id'];
            $this->details[] = $detail;
        }
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
    }

    public function validate() {
        $valid = $this->header->validate();

        $valid = $this->validateDetailsCount() && $valid;

        if (count($this->details) > 0) {
            foreach ($this->details as $detail) {
                $fields = array('quantity', 'product_id');
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

    public function flush() {
        //delete previous inventory
        Inventory::model()->deleteAllByAttributes(array(
            'transaction_ordinal' => $this->header->cn_ordinal,
            'transaction_month' => $this->header->cn_month,
            'transaction_year' => $this->header->cn_year,
            //				'product_id' => $detail->product_id,
            'branch_id' => $this->header->branch_id,
            'transaction_type' => 2,
        ));

        $valid = $this->header->save(false);

        //delete previous journal accounting
        JournalAccounting::model()->deleteAllByAttributes(array(
            'transaction_number' => $this->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT),
            'branch_id' => $this->header->branch_id,
            'type' => 8,
        ));

        foreach ($this->details as $detail) {
            if ($detail->quantity <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->purchase_return_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;

            $transactionSubject = $this->header->receiveHeader((array(
                        'scopes' => 'resetScope',
                        'with' => array(
                            'purchaseHeader:resetScope' => array(
                                'with' => 'supplier:resetScope',
                            )
                        ),
                            )))->purchaseHeader->supplier->company;

            if ($detail->is_inactive == 0) {
                $inventory = new Inventory();
                $inventory->transaction_ordinal = $this->header->cn_ordinal;
                $inventory->transaction_month = $this->header->cn_month;
                $inventory->transaction_year = $this->header->cn_year;
                $inventory->transaction_type = 2;
                $inventory->transaction_subject = $transactionSubject;
                $inventory->product_id = $detail->product_id;
                $inventory->admin_id = $this->header->admin_id;
                $inventory->branch_id = $this->header->branch_id;
                $inventory->date = $this->header->date;
                $inventory->quantity_out = $detail->quantity;
                $inventory->price = $detail->product->costOfGoodsSold;
                $inventory->warehouse_id = $this->header->warehouse_id;

                $valid = $inventory->save(false) && $valid;
            }
        }

        //add accounting journal debit
        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.name';
        $criteria->compare('t.branch_id', $this->header->branch_id);
        $criteria->compare('t.code', '420-002'); //PurchaseReturn account code MUST BE 420-002. If changed, journal accounting will save empty row
        $account = Account::model()->find($criteria);
        $accountingJournalDebit = AccountingJournalHelper::make('debit',
                        $this->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT),
                        $this->header->date,
                        $account->id,
                        $this->header->branch_id,
                        CHtml::value($this->header, 'grandTotal'),
                        8,
                        $account->name);
        $valid = $valid && $accountingJournalDebit->save();

        //add accounting journal credit
        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.name';
        $criteria->compare('t.branch_id', $this->header->branch_id);
        $criteria->compare('t.code', '420-001'); //Purchase account code MUST BE 420-001. If changed, journal accounting will save empty row
        $account = Account::model()->find($criteria);
        $accountingJournalDebit = AccountingJournalHelper::make('credit',
                        $this->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT),
                        $this->header->date,
                        $account->id,
                        $this->header->branch_id,
                        CHtml::value($this->header, 'grandTotal'),
                        8,
                        $account->name);
        $valid = $valid && $accountingJournalDebit->save();

        return $valid;
    }

    public function getSubTotal($receiveId = null) {
        $total = 0.00;
        foreach ($this->details as $detail)
            $total += $detail->getTotal($receiveId);

        return $total;
    }

    public function getCalculatedTax($receiveId = null) {
        return $this->getSubTotal($receiveId) * $this->header->tax / 100;
    }

    public function getGrandTotal($receiveId = null) {
        return $this->getSubTotal($receiveId) + $this->getCalculatedTax($receiveId) + $this->header->shipping_fee;
    }
}
