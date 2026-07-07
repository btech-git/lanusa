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
 * @property integer $receive_header_id
 * @property integer $warehouse_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property PurchaseReturnDetail[] $purchaseReturnDetails
 * @property ReceiveHeader $receiveHeader
 * @property Admin $admin
 * @property Warehouse $warehouse
 * @property Branch $branch
 */
class PurchaseReturnHeaderBase extends MonthlyTransactionActiveRecord
{
	public function tableName()
	{
		return 'tblla_purchase_return_header';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_month, cn_year, date, receive_header_id, warehouse_id, branch_id, admin_id', 'required'),
			array('cn_ordinal, cn_month, cn_year, tax, receive_header_id, warehouse_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('shipping_fee', 'length', 'max'=>18),
			array('note', 'safe'),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_month, cn_year, date, tax, shipping_fee, note, receive_header_id, warehouse_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'purchaseReturnDetails' => array(self::HAS_MANY, 'PurchaseReturnDetail', 'purchase_return_header_id'),
			'receiveHeader' => array(self::BELONGS_TO, 'ReceiveHeader', 'receive_header_id'),
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cn_ordinal' => 'Cn Ordinal',
			'cn_month' => 'Cn Month',
			'cn_year' => 'Cn Year',
			'date' => 'Date',
			'tax' => 'Tax',
			'shipping_fee' => 'Shipping Fee',
			'note' => 'Note',
			'receive_header_id' => 'Receive Header',
			'warehouse_id' => 'Warehouse',
			'branch_id' => 'Branch',
			'admin_id' => 'Admin',
			'is_non_tax' => 'Is Non Tax',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('t.date', $this->date, true);
		$criteria->compare('tax', $this->tax);
		$criteria->compare('shipping_fee', $this->shipping_fee, true);
		$criteria->compare('t.note', $this->note, true);
		$criteria->compare('receive_header_id', $this->receive_header_id);
		$criteria->compare('t.warehouse_id', $this->warehouse_id);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('is_non_tax', $this->is_non_tax);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}