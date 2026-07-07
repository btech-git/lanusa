<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property integer $tax
 * @property string $shipping_fee
 * @property string $note
 * @property integer $sale_invoice_id
 * @property integer $warehouse_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property SaleReturnDetail[] $saleReturnDetails
 * @property Warehouse $warehouse
 * @property Admin $admin
 * @property SaleInvoice $saleInvoice
 * @property Branch $branch
 */
class SaleReturnHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_sale_return_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, sale_invoice_id, warehouse_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, tax, sale_invoice_id, warehouse_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('shipping_fee', 'length', 'max' => 18),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, tax, shipping_fee, note, sale_invoice_id, warehouse_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'saleReturnDetails' => array(self::HAS_MANY, 'SaleReturnDetail', 'sale_return_header_id'),
            'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'saleInvoice' => array(self::BELONGS_TO, 'SaleInvoice', 'sale_invoice_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
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
            'shipping_fee' => 'Shipping Fee',
            'note' => 'Note',
            'sale_invoice_id' => 'Sale Invoice',
            'warehouse_id' => 'Warehouse',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'is_non_tax' => 'Is Non Tax',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('tax', $this->tax);
        $criteria->compare('shipping_fee', $this->shipping_fee, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('sale_invoice_id', $this->sale_invoice_id);
        $criteria->compare('t.warehouse_id', $this->warehouse_id);
        $criteria->compare('branch_id', $this->branch_id);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->compare('is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

//    public function getSubTotal() {
//        $total = 0.00;
//
//        foreach ($this->salesReturnDetails as $detail)
//            $total += $detail->total;
//
//        return $total;
//    }
//
//    public function getCalculatedTax() {
//        return ($this->subTotal) * ($this->tax / 100);
//    }
//
//    public function getGrandTotal() {
//        return $this->subTotal + $this->calculatedTax + $this->shipping_fee;
//    }
}