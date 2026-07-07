<?php

/**
 * @property integer $id
 * @property integer $purchase_invoice_header_id
 * @property integer $purchase_header_id
 * @property integer $is_inactive
 *
 * @property PurchaseHeader $purchaseHeader
 * @property PurchaseInvoiceHeader $purchaseInvoiceHeader
 */
class PurchaseInvoiceDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_purchase_invoice_detail';
    }

    public function rules() {
        return array(
            array('purchase_invoice_header_id, purchase_header_id', 'required'),
            array('purchase_invoice_header_id, purchase_header_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, purchase_invoice_header_id, purchase_header_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchaseHeader' => array(self::BELONGS_TO, 'PurchaseHeader', 'purchase_header_id'),
            'purchaseInvoiceHeader' => array(self::BELONGS_TO, 'PurchaseInvoiceHeader', 'purchase_invoice_header_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'purchase_invoice_header_id' => 'Purchase Invoice Header',
            'purchase_header_id' => 'Purchase Header',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.purchase_invoice_header_id', $this->purchase_invoice_header_id);
        $criteria->compare('t.purchase_header_id', $this->purchase_header_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
