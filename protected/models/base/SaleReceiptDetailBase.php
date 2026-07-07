<?php

/**
 * @property integer $id
 * @property string $memo
 * @property integer $sale_invoice_id
 * @property integer $sale_receipt_header_id
 * @property integer $is_inactive
 *
 * @property SaleInvoice $saleInvoice
 * @property SaleReceiptHeader $saleReceiptHeader
 */
class SaleReceiptDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_sale_receipt_detail';
    }

    public function rules() {
        return array(
            array('sale_invoice_id, sale_receipt_header_id', 'required'),
            array('sale_invoice_id, sale_receipt_header_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('memo', 'length', 'max' => 60),
            // The following rule is used by search().
            array('id, memo, sale_invoice_id, sale_receipt_header_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'saleInvoice' => array(self::BELONGS_TO, 'SaleInvoice', 'sale_invoice_id'),
            'saleReceiptHeader' => array(self::BELONGS_TO, 'SaleReceiptHeader', 'sale_receipt_header_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'memo' => 'Memo',
            'sale_invoice_id' => 'Sale Invoice',
            'sale_receipt_header_id' => 'Sale Receipt Header',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.sale_invoice_id', $this->sale_invoice_id);
        $criteria->compare('t.sale_receipt_header_id', $this->sale_receipt_header_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }
}