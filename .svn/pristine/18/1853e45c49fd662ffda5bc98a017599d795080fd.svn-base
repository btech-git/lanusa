<?php

class Transfer extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $transferHeader = TransferHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($transferHeader !== null)
            $this->header->setCodeNumber($transferHeader->cn_ordinal, $transferHeader->cn_month, $transferHeader->cn_year, $transferHeader->branch_id);

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
                $detail = new TransferDetail();
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
                $fields = array('quantity', 'unit_price', 'product_id');
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
            if ($detail->quantity <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->transfer_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;

            Inventory::model()->deleteAllByAttributes(array(
                'transaction_ordinal' => $this->header->cn_ordinal,
                'transaction_month' => $this->header->cn_month,
                'transaction_year' => $this->header->cn_year,
                'product_id' => $detail->product_id,
                'branch_id' => $this->header->branch_id,
                'transaction_type' => 6,
            ));

            if ($detail->is_inactive == 0) {
                $inventoryIn = new Inventory();
                $inventoryIn->transaction_ordinal = $this->header->cn_ordinal;
                $inventoryIn->transaction_month = $this->header->cn_month;
                $inventoryIn->transaction_year = $this->header->cn_year;
                $inventoryIn->transaction_type = 6;
                $inventoryIn->transaction_subject = 'transfer in';
                $inventoryIn->product_id = $detail->product_id;
                $inventoryIn->admin_id = $this->header->admin_id;
                $inventoryIn->branch_id = $this->header->branch_id;
                $inventoryIn->date = $this->header->date;
                $inventoryIn->quantity_in = $detail->quantity;
                $inventoryIn->price = $detail->product->costOfGoodsSold;
                $inventoryIn->warehouse_id = $this->header->warehouse_id_to;

                $valid = $inventoryIn->save(false) && $valid;

                $inventoryOut = new Inventory();
                $inventoryOut->transaction_ordinal = $this->header->cn_ordinal;
                $inventoryOut->transaction_month = $this->header->cn_month;
                $inventoryOut->transaction_year = $this->header->cn_year;
                $inventoryOut->transaction_type = 6;
                $inventoryOut->transaction_subject = 'transfer out';
                $inventoryOut->product_id = $detail->product_id;
                $inventoryOut->admin_id = $this->header->admin_id;
                $inventoryOut->branch_id = $this->header->branch_id;
                $inventoryOut->date = $this->header->date;
                $inventoryOut->quantity_out = $detail->quantity;
                $inventoryOut->price = $detail->product->costOfGoodsSold;
                $inventoryOut->warehouse_id = $this->header->warehouse_id_from;

                $valid = $inventoryOut->save(false) && $valid;
            }
        }

        return $valid;
    }
}
