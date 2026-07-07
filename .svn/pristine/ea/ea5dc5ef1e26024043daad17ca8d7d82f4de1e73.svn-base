<?php

class Delivery extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $deliveryHeader = DeliveryHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($deliveryHeader !== null)
            $this->header->setCodeNumber($deliveryHeader->cn_ordinal, $deliveryHeader->cn_month, $deliveryHeader->cn_year, $deliveryHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $sql = "SELECT p.id, p.product_id, p.quantity - SUM(COALESCE(r.quantity, 0)) AS quantity_ordered
                FROM " . SaleDetail::model()->tableName() . " p
                LEFT OUTER JOIN " . DeliveryDetail::model()->tableName() . " r ON p.id = r.sale_detail_id AND r.is_inactive = 0
                WHERE p.sale_header_id = :sale_header_id AND p.is_inactive = 0
                GROUP BY p.id
                HAVING quantity_ordered > 0";

        $resultSet = CActiveRecord::$db->createCommand($sql)->queryAll(true, array(':sale_header_id' => $id));

        $this->details = array();

        foreach ($resultSet as $row) {
            $detail = new DeliveryDetail();
            $detail->product_id = $row['product_id'];
            $detail->sale_detail_id = $row['id'];

            $this->details[] = $detail;
        }
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
    }

    public function validateDetailsCount() {
        $valid = true;
        if (count($this->details) === 0) {
            $valid = false;
            $this->header->addError('error', 'Form tidak ada data untuk insert database. Minimal satu data detail untuk melakukan penyimpanan.');
        }

        return $valid;
    }

    public function validate() {
        $valid = $this->header->validate();
        if (!$valid)
            $this->header->addError('error', 'Header Error');

        if ($this->header->isNewRecord) {
            $valid = $this->validateDetailsCount() && $valid;
            if (!$valid) {
                $this->header->addError('error', 'Details Count Error');
            }
        }
        
        foreach ($this->details as $detail) {
            if ($detail->is_inactive === 0) {
                $fields = array('quantity', 'product_id', 'warehouse_id');
                $valid = $detail->validate($fields) && $valid;
                
                if (!$valid) {
                    $this->header->addError('error', 'Detail Error');
                }
            }
        } 

        return $valid;
    }

    public function flush() {
        Inventory::model()->deleteAllByAttributes(array(
            'transaction_ordinal' => $this->header->cn_ordinal,
            'transaction_month' => $this->header->cn_month,
            'transaction_year' => $this->header->cn_year,
//			'product_id' => $detail->product_id,
            'branch_id' => $this->header->branch_id,
            'transaction_type' => 3,
        ));

        $valid = $this->header->save(false);

        foreach ($this->details as $detail) {
            if ($detail->quantity <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->delivery_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;

            $saleDetail = SaleDetail::model()->findByAttributes(array('sale_header_id' => $this->header->sale_header_id, 'product_id' => $detail->product_id));

            if ($detail->is_inactive == 0) {
                $inventory = new Inventory();
                $inventory->transaction_ordinal = $this->header->cn_ordinal;
                $inventory->transaction_month = $this->header->cn_month;
                $inventory->transaction_year = $this->header->cn_year;
                $inventory->transaction_type = 3;
                $inventory->transaction_subject = $this->header->saleHeader->customer->company;
                $inventory->product_id = $detail->product_id;
                $inventory->admin_id = $this->header->admin_id;
                $inventory->branch_id = $this->header->branch_id;
                $inventory->date = $this->header->date;
                $inventory->quantity_out = $detail->quantity;
                $inventory->price = empty($detail->product->costOfGoodsSold) ? $saleDetail->unit_price : $detail->product->costOfGoodsSold;
                $inventory->warehouse_id = $detail->warehouse_id;

                $valid = $inventory->save(false) && $valid;
                if (!$valid)
                    $this->header->addError('error', 'Inventory Error');
            }
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

    public function getSubTotal() {
        $total = 0.00;
        foreach ($this->details as $detail)
            $total += $detail->total;

        return $total;
    }

    public function getCalculatedDiscount() {
        return $this->subTotal * $this->header->discount / 100;
    }

    public function getCalculatedTax() {
        return $this->totalBeforeTax * $this->header->tax / 100;
    }

    public function getGrandTotal() {
        return $this->totalBeforeTax + $this->calculatedTax + ($this->header->saleHeader === null) ? 0.00 : $this->header->shipping_fee;
    }

    public function getTotalBeforeTax() {
        return $this->subTotal - $this->calculatedDiscount - (($this->header->saleDownpayment === null) ? 0 : $this->header->saleDownpayment->amount);
    }

}
