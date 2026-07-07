<?php

/**
 * @property integer $id
 * @property string $memo
 * @property integer $purchase_receipt_header_id
 * @property integer $receive_header_id
 * @property integer $is_inactive
 *
 * @property PurchaseReceiptHeader $purchaseReceiptHeader
 * @property ReceiveHeader $receiveHeader
 */
class PurchaseReceiptDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_purchase_receipt_detail';
    }

    public function rules() {
        return array(
            array('purchase_receipt_header_id, receive_header_id', 'required'),
            array('purchase_receipt_header_id, receive_header_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('memo', 'safe'),
            // The following rule is used by search().
            array('id, memo, purchase_receipt_header_id, receive_header_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchaseReceiptHeader' => array(self::BELONGS_TO, 'PurchaseReceiptHeader', 'purchase_receipt_header_id'),
            'receiveHeader' => array(self::BELONGS_TO, 'ReceiveHeader', 'receive_header_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'memo' => 'Memo',
            'purchase_receipt_header_id' => 'Purchase Receipt Header',
            'receive_header_id' => 'Receive Header',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.purchase_receipt_header_id', $this->purchase_receipt_header_id);
        $criteria->compare('t.receive_header_id', $this->receive_header_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
