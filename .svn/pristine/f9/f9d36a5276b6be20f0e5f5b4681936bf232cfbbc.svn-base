<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property integer $tax
 * @property string $discount
 * @property string $shipping_fee
 * @property string $note
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $supplier_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property PurchaseDetail[] $purchaseDetails
 * @property Supplier $supplier
 * @property Admin $admin
 * @property Branch $branch
 * @property PurchaseInvoiceDetail[] $purchaseInvoiceDetails
 * @property ReceiveHeader[] $receiveHeaders
 */
class PurchaseHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_purchase_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, branch_id, admin_id, supplier_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, tax, branch_id, admin_id, supplier_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('discount, shipping_fee', 'length', 'max' => 18),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, tax, discount, shipping_fee, note, branch_id, admin_id, supplier_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchaseDetails' => array(self::HAS_MANY, 'PurchaseDetail', 'purchase_header_id'),
            'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplier_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'purchaseInvoiceDetails' => array(self::HAS_MANY, 'PurchaseInvoiceDetail', 'purchase_header_id'),
            'receiveHeaders' => array(self::HAS_MANY, 'ReceiveHeader', 'purchase_header_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cn_ordinal' => 'Cn Ordinal',
            'cn_month' => 'Cn Month',
            'cn_year' => 'Cn Year',
            'date' => 'Date',
            'tax' => 'Tax',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'note' => 'Note',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'supplier_id' => 'Supplier',
            'is_non_tax' => 'Tax Type',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.tax', $this->tax);
        $criteria->compare('t.discount', $this->discount, true);
        $criteria->compare('t.shipping_fee', $this->shipping_fee, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.supplier_id', $this->supplier_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
