<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $note
 * @property integer $sale_receipt_header_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property SalePaymentDetail[] $salePaymentDetails
 * @property Admin $admin
 * @property SaleReceiptHeader $saleReceiptHeader
 * @property Branch $branch
 */
class SalePaymentHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_sale_payment_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, sale_receipt_header_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, sale_receipt_header_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, note, sale_receipt_header_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'salePaymentDetails' => array(self::HAS_MANY, 'SalePaymentDetail', 'sale_payment_header_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'saleReceiptHeader' => array(self::BELONGS_TO, 'SaleReceiptHeader', 'sale_receipt_header_id'),
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
            'note' => 'Note',
            'sale_receipt_header_id' => 'Sale Receipt Header',
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
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.sale_receipt_header_id', $this->sale_receipt_header_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
