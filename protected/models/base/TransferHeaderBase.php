<?php

/**
 * @property integer $id
 * @property string $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $note
 * @property integer $warehouse_id_from
 * @property integer $warehouse_id_to
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property TransferDetail[] $transferDetails
 * @property Warehouse $warehouseIdFrom
 * @property Warehouse $warehouseIdTo
 * @property Admin $admin
 * @property Branch $branch
 */
class TransferHeaderBase extends MonthlyTransactionActiveRecord {

    public function tableName() {
        return 'tblla_transfer_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, warehouse_id_from, warehouse_id_to, branch_id, admin_id', 'required'),
            array('cn_month, cn_year, warehouse_id_from, warehouse_id_to, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly' => true),
            array('cn_ordinal', 'length', 'max' => 20),
            array('note', 'safe'),
			array('warehouse_id_to', 'compare', 'compareAttribute' => 'warehouse_id_from', 'operator' => '!=', 'strict' => 'true', 'message'=>'Gudang asal tidak boleh sama dengan gudang tujuan'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, note, warehouse_id_from, warehouse_id_to, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'transferDetails' => array(self::HAS_MANY, 'TransferDetail', 'transfer_header_id'),
            'warehouseIdFrom' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id_from'),
            'warehouseIdTo' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id_to'),
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
            'note' => 'Note',
            'warehouse_id_from' => 'Warehouse Id From',
            'warehouse_id_to' => 'Warehouse Id To',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'is_non_tax' => 'Is Non Tax',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('cn_ordinal', $this->cn_ordinal, true);
        $criteria->compare('cn_month', $this->cn_month);
        $criteria->compare('cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('warehouse_id_from', $this->warehouse_id_from);
        $criteria->compare('warehouse_id_to', $this->warehouse_id_to);
        $criteria->compare('branch_id', $this->branch_id);
        $criteria->compare('admin_id', $this->admin_id);
        $criteria->compare('is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}