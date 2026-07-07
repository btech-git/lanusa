<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $due_date
 * @property string $note
 * @property string $grand_total
 * @property string $total_payment
 * @property integer $customer_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property SalePaymentHeader[] $salePaymentHeaders
 * @property SaleReceiptDetail[] $saleReceiptDetails
 * @property Admin $admin
 * @property Customer $customer
 * @property Branch $branch
 */
class SaleReceiptHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_sale_receipt_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, due_date, customer_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, customer_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('grand_total, total_payment', 'length', 'max' => 18),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, due_date, note, grand_total, total_payment, customer_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'salePaymentHeaders' => array(self::HAS_MANY, 'SalePaymentHeader', 'sale_receipt_header_id'),
            'saleReceiptDetails' => array(self::HAS_MANY, 'SaleReceiptDetail', 'sale_receipt_header_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
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
            'due_date' => 'Due Date',
            'note' => 'Note',
            'grand_total' => 'Grand Total',
            'total_payment' => 'Total Payment',
            'customer_id' => 'Customer',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'is_non_tax' => 'Is Non Tax',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.due_date', $this->due_date, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.grand_total', $this->grand_total, true);
        $criteria->compare('t.total_payment', $this->total_payment, true);
        $criteria->compare('t.customer_id', $this->customer_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
