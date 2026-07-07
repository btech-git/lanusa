<?php

/**
 * @property integer $id
 * @property string $quantity
 * @property integer $purchase_return_header_id
 * @property integer $product_id
 * @property integer $is_inactive
 *
 * @property PurchaseReturnHeader $purchaseReturnHeader
 * @property Product $product
 */
class PurchaseReturnDetailBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_purchase_return_detail';
	}

	public function rules()
	{
		return array(
			array('purchase_return_header_id, product_id', 'required'),
			array('purchase_return_header_id, product_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('quantity', 'length', 'max'=>10),
			// The following rule is used by search().
			array('id, quantity, purchase_return_header_id, product_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'purchaseReturnHeader' => array(self::BELONGS_TO, 'PurchaseReturnHeader', 'purchase_return_header_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'quantity' => 'Quantity',
			'purchase_return_header_id' => 'Purchase Return Header',
			'product_id' => 'Product',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('quantity', $this->quantity, true);
		$criteria->compare('purchase_return_header_id', $this->purchase_return_header_id);
		$criteria->compare('product_id', $this->product_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}