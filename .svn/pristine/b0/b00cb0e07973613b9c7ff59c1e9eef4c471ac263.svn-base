<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $fee_amount
 * @property string $note
 * @property integer $customer_id
 * @property integer $account_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $tax_item_value
 * @property string $tax_item_amount
 * @property integer $tax_service_value
 * @property string $tax_service_amount
 * @property integer $is_inactive
 *
 * @property Customer $customer
 * @property Account $account
 * @property Admin $admin
 */
class FeeInvoiceBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_fee_invoice';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, customer_id, account_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, customer_id, account_id, tax_item_value, tax_service_value, is_inactive, branch_id, admin_id', 'numerical', 'integerOnly' => true),
            array('fee_amount, tax_item_amount, tax_service_amount', 'length', 'max' => 18),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, fee_amount, note, customer_id, account_id, branch_id, tax_item_value, tax_item_amount, tax_service_value, tax_service_amount, is_inactive, admin_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cn_ordinal' => 'Cn Ordinal',
            'cn_month' => 'Cn Month',
            'cn_year' => 'Cn Year',
            'date' => 'Date',
            'fee_amount' => 'Fee Amount',
            'note' => 'Note',
            'customer_id' => 'Customer',
            'account_id' => 'Account',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin Input',
            'tax_item_value' => 'Tax Item Value',
            'tax_item_amount' => 'Tax Item Amount',
            'tax_service_value' => 'Tax Service Value',
            'tax_service_amount' => 'Tax Service Amount',
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
        $criteria->compare('t.fee_amount', $this->fee_amount, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.customer_id', $this->customer_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.account_id', $this->account_id);
        $criteria->compare('t.tax_item_value', $this->tax_item_value);
        $criteria->compare('t.tax_item_amount', $this->tax_item_amount, true);
        $criteria->compare('t.tax_service_value', $this->tax_service_value);
        $criteria->compare('t.tax_service_amount', $this->tax_service_amount, true);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
