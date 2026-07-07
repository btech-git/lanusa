<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $reference
 * @property string $supplier_tax_number
 * @property string $note
 * @property integer $branch_id
 * @property integer $purchase_header_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property PurchaseReceiptDetail[] $purchaseReceiptDetails
 * @property PurchaseReturnHeader[] $purchaseReturnHeaders
 * @property ReceiveDetail[] $receiveDetails
 * @property PurchaseHeader $purchaseHeader
 * @property Admin $admin
 * @property Branch $branch
 */
class ReceiveHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_receive_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, branch_id, purchase_header_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, branch_id, purchase_header_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('reference', 'length', 'max' => 60),
            array('supplier_tax_number', 'length', 'max' => 255),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, reference, supplier_tax_number, note, branch_id, purchase_header_id, admin_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchaseReceiptDetails' => array(self::HAS_MANY, 'PurchaseReceiptDetail', 'receive_header_id'),
            'purchaseReturnHeaders' => array(self::HAS_MANY, 'PurchaseReturnHeader', 'receive_header_id'),
            'receiveDetails' => array(self::HAS_MANY, 'ReceiveDetail', 'receive_header_id'),
            'purchaseHeader' => array(self::BELONGS_TO, 'PurchaseHeader', 'purchase_header_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
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
            'reference' => 'Reference',
            'supplier_tax_number' => 'Supplier Tax Number',
            'note' => 'Note',
            'branch_id' => 'Branch',
            'purchase_header_id' => 'Purchase Header',
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
        $criteria->compare('t.reference', $this->reference, true);
        $criteria->compare('t.supplier_tax_number', $this->supplier_tax_number, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.purchase_header_id', $this->purchase_header_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
