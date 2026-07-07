<?php

class PurchaseReceiptHeader extends PurchaseReceiptHeaderBase {

    const CN_CONSTANT = 'PRT';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function searchNotFullyPaid() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('cn_ordinal', $this->cn_ordinal);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('note', $this->note, true);
//		$criteria->compare('total_payment', $this->total_payment, true);
//		$criteria->compare('grand_total', $this->grand_total, true);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('branch_id', $this->branch_id);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->compare('is_non_tax', $this->is_non_tax);
        $criteria->compare('is_inactive', $this->is_inactive);

//		$criteria->addCondition('t.total_payment < t.grand_total');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getTotalPurchase() {
        $total = 0.00;

        foreach ($this->purchaseReceiptDetails as $detail) {
            if ($detail->is_inactive == 0) {
                $total += $detail->totalPurchase;
            }
        }

        return $total;
    }

//    public function getTotalCalculatedTax() {
//        $total = 0.00;
//
//        foreach ($this->purchaseReceiptDetails as $detail) {
//            $total += $detail->calculatedTax;
//        }
//
//        return $total;
//    }
//
//    public function getGrandTotal() {
//        return $this->totalPurchase + $this->totalCalculatedTax;
//    }

    public function getTotalReceivePrice() {
        return $this->grand_total;
    }

    public function getTotalReceive() {
        $total = 0.00;

        foreach ($this->purchaseReceiptDetails as $detail)
            $total += $detail->receiveHeader->grandTotalReceipt;

        return $total;
    }

    public function searchByPurchasePayment() {
        $criteria = new CDbCriteria;

        $criteria->order = 't.id DESC';

        $criteria->condition = "EXISTS (
			SELECT grand_total - payment_total  AS remaining 
			FROM " . PurchaseReceiptHeader::model()->tableName() . " 
			WHERE t.id = id
			HAVING remaining > 5
		)";

        //$criteria->condition = SqlViewGenerator::purchasePaymentRemaining();

        $criteria->compare('cn_ordinal', $this->cn_ordinal);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('supplier_id', $this->supplier_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchByPurchaseCheque() {
        $criteria = new CDbCriteria;

        $criteria->condition = "t.id NOT IN (
            SELECT purchase_receipt_header_id 
            FROM " . PurchaseCheque::model()->tableName() . " 
            WHERE is_inactive = 0
        )";

        $criteria->compare('cn_ordinal', $this->cn_ordinal);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('supplier_id', $this->supplier_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function getTotalInvoice() {
        $total = 0.00;

        foreach ($this->purchaseReceiptDetails as $detail)
            $total += $detail->receiveHeader->grandTotalReceipt;

        return $total;
    }

    public function getPayment() {
        $payment = 0.00;

        foreach ($this->purchasePaymentHeaders as $paymentHeader) {
            foreach ($paymentHeader->purchasePaymentDetails as $paymentDetail) {
                if ($paymentDetail->is_inactive == 0) {
                    $payment += $paymentDetail->amount;
                }
            }
        }

        return $payment;
    }

    public function getRemaining() {
        return $this->grand_total - $this->payment_total;
    }

//	 public function getTotalReceivePrice()
//    {
//        $total = 0.00;
//
//        foreach ($this->purchaseReceiptDetails as $detail)
//            $total += $detail->receiveHeader->grandTotalReceipt;
//
//        return $total;
//    }

    public function searchWithPaging() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('grand_total', $this->grand_total, true);
        $criteria->compare('payment_total', $this->payment_total, true);
        $criteria->compare('t.supplier_id', $this->supplier_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('is_non_tax', $this->is_non_tax);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

}
