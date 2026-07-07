<?php

class ReceiveHeader extends ReceiveHeaderBase {
    const CN_CONSTANT = 'RCV';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getTotalPurchase() {
        $total = 0.00;

        foreach ($this->receiveDetails as $detail) {
            $total += $detail->total;
        }

        return ((int)$this->purchaseHeader->is_non_tax == PurchaseHeader::INCLUDE_TAX) ? $total/ 1.11 : $total;
    }

    public function getTotalQuantity() {
        $total = 0.00;

        foreach ($this->receiveDetails as $detail)
            $total += $detail->quantity;

        return $total;
    }

    public function getPurchaseTax() {
        return $this->totalPurchase * $this->purchaseHeader->tax / 100;
    }

    public function getGrandTotalReceipt() {
        return $this->totalPurchase + $this->purchaseTax;
    }

    public function searchByPurchaseReceipt() {
        $criteria = new CDbCriteria;

        $criteria->condition = "
            t.id NOT IN (
                SELECT receive_header_id 
                FROM " . PurchaseReceiptDetail::model()->tableName() . " 
                WHERE is_inactive = 0
            )
        ";

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function searchByPurchaseReturn() {
        $criteria = new CDbCriteria;

        $criteria->condition = 'EXISTS (
            ' . SqlViewGenerator::quantityReceive() . '
            WHERE t.id = receive.id
            GROUP BY receive.id, receive.product_id
            HAVING quantity_received > 0
        )';

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function searchWithPaging() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('reference', $this->reference, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.purchase_header_id', $this->purchase_header_id);
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