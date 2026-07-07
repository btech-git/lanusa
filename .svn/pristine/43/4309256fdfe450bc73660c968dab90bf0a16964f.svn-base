<?php

/**
 * @property integer $id
 * @property integer $transaction_ordinal
 * @property integer $transaction_month
 * @property integer $transaction_year
 * @property string $date
 * @property integer $transaction_type
 * @property string $transaction_subject
 * @property string $quantity_in
 * @property string $quantity_out
 * @property string $price
 * @property integer $product_id
 * @property integer $warehouse_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_inactive
 *
 * @property Product $product
 * @property Admin $admin
 * @property Warehouse $warehouse
 * @property Branch $branch
 */
class InventoryBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_inventory';
	}

	public function rules()
	{
		return array(
			array('transaction_ordinal, transaction_month, transaction_year, date, product_id, warehouse_id, branch_id, admin_id', 'required'),
			array('transaction_ordinal, transaction_month, transaction_year, transaction_type, product_id, warehouse_id, branch_id, admin_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('transaction_subject', 'length', 'max'=>60),
			array('quantity_in, quantity_out', 'length', 'max'=>10),
			array('price', 'length', 'max'=>18),
			// The following rule is used by search().
			array('id, transaction_ordinal, transaction_month, transaction_year, date, transaction_type, transaction_subject, quantity_in, quantity_out, price, product_id, warehouse_id, branch_id, admin_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'transaction_ordinal' => 'Transaction Ordinal',
			'transaction_month' => 'Transaction Month',
			'transaction_year' => 'Transaction Year',
			'date' => 'Date',
			'transaction_type' => 'Transaction Type',
			'transaction_subject' => 'Transaction Subject',
			'quantity_in' => 'Quantity In',
			'quantity_out' => 'Quantity Out',
			'price' => 'Price',
			'product_id' => 'Product',
			'warehouse_id' => 'Warehouse',
			'branch_id' => 'Branch',
			'admin_id' => 'Admin',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('transaction_ordinal', $this->transaction_ordinal);
		$criteria->compare('transaction_month', $this->transaction_month);
		$criteria->compare('transaction_year', $this->transaction_year);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('transaction_type', $this->transaction_type);
		$criteria->compare('transaction_subject', $this->transaction_subject, true);
		$criteria->compare('quantity_in', $this->quantity_in, true);
		$criteria->compare('quantity_out', $this->quantity_out, true);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('product_id', $this->product_id);
		$criteria->compare('warehouse_id', $this->warehouse_id);
		$criteria->compare('branch_id', $this->branch_id);
		$criteria->compare('admin_id', $this->admin_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}