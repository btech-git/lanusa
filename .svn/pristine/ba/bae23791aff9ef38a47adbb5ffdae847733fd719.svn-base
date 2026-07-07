<?php

class SaleReceiptHeader extends SaleReceiptHeaderBase {

    const CN_CONSTANT = 'TT';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function searchBySalePayment() {
        $criteria = new CDbCriteria;

        $criteria->order = 't.id DESC';
        $criteria->condition = "(grand_total - total_payment) > 100";

        $criteria->compare('cn_ordinal', $this->cn_ordinal);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('customer_id', $this->customer_id);
//		$criteria->addCondition('total_payment < grand_total');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchByReceipt() {
        $criteria = new CDbCriteria;

        $criteria->condition = "t.id NOT IN (SELECT sale_receipt_header_id FROM tblla_sale_cheque_detail WHERE is_inactive = 0)";

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.customer_id', $this->customer_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getTotalInvoice() {
        $total = 0.00;

        foreach ($this->saleReceiptDetails as $detail)
            $total += ($detail->saleInvoice === null) ? 0.00 : $detail->saleInvoice->grandTotal;

        return $total;
    }

    public function getPayment() {
        $payment = 0.00;

        foreach ($this->salePaymentHeaders as $paymentHeader) {
            foreach ($paymentHeader->salePaymentDetails as $paymentDetail) {
                $amountPayment = (int) $paymentDetail->is_inactive === 0 ? $paymentDetail->amount : 0.00;
                $payment += $amountPayment;
            }
        }

        return $payment;
    }

    public function getRemaining() {
        return $this->totalInvoice - $this->payment;
    }

    public function searchWithPaging() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('cn_ordinal', $this->cn_ordinal);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('due_date', $this->due_date, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('admin_id', $this->admin_id);
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
