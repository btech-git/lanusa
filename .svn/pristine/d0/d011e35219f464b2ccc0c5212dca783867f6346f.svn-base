<?php

/**
 * @property integer $id
 * @property string $quantity
 * @property integer $sale_return_header_id
 * @property integer $product_id
 * @property integer $is_inactive
 *
 * @property Product $product
 * @property SaleReturnHeader $saleReturnHeader
 */
class SaleReturnDetailBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_sale_return_detail';
	}

	public function rules()
	{
		return array(
			array('sale_return_header_id, product_id', 'required'),
			array('sale_return_header_id, product_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('quantity', 'length', 'max'=>10),
			// The following rule is used by search().
			array('id, quantity, sale_return_header_id, product_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'saleReturnHeader' => array(self::BELONGS_TO, 'SaleReturnHeader', 'sale_return_header_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'quantity' => 'Quantity',
			'sale_return_header_id' => 'Sale Return Header',
			'product_id' => 'Product',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.quantity', $this->quantity, true);
		$criteria->compare('t.sale_return_header_id', $this->sale_return_header_id);
		$criteria->compare('t.product_id', $this->product_id);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}