<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $reference
 * @property string $discount
 * @property string $shipping_fee
 * @property string $note
 * @property integer $delivery_header_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 * @property integer $tax_percentage
 *
 * @property Admin $admin
 * @property Branch $branch
 * @property DeliveryHeader $deliveryHeader
 * @property SaleReceiptDetail[] $saleReceiptDetails
 * @property SaleReturnHeader[] $saleReturnHeaders
 * @property TaxForm[] $taxForms
 */
class SaleInvoiceBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_sale_invoice';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, delivery_header_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, delivery_header_id, branch_id, admin_id, is_non_tax, is_inactive, tax_percentage', 'numerical', 'integerOnly' => true),
            array('reference', 'length', 'max' => 60),
            array('discount, shipping_fee', 'length', 'max' => 18),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, reference, discount, shipping_fee, note, delivery_header_id, branch_id, admin_id, is_non_tax, is_inactive, tax_percentage', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'deliveryHeader' => array(self::BELONGS_TO, 'DeliveryHeader', 'delivery_header_id'),
            'saleReceiptDetails' => array(self::HAS_MANY, 'SaleReceiptDetail', 'sale_invoice_id'),
            'saleReturnHeaders' => array(self::HAS_MANY, 'SaleReturnHeader', 'sale_invoice_id'),
            'taxForms' => array(self::HAS_MANY, 'TaxForm', 'sale_invoice_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cn_ordinal' => 'Cn Ordinal',
            'cn_month' => 'Cn Month',
            'cn_year' => 'Cn Year',
            'date' => 'Date',
            'reference' => 'Reference',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'note' => 'Note',
            'delivery_header_id' => 'Delivery Header',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'is_non_tax' => 'Is Non Tax',
            'is_inactive' => 'Is Inactive',
            'tax_percentage' => 'Tax %',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.reference', $this->reference, true);
        $criteria->compare('t.discount', $this->discount, true);
        $criteria->compare('t.shipping_fee', $this->shipping_fee, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.delivery_header_id', $this->delivery_header_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);
        $criteria->compare('t.tax_percentage', $this->tax_percentage);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
