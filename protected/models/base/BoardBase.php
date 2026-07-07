<?php

/**
 * @property integer $id
 * @property string $name
 * @property string $position
 * @property integer $is_inactive
 *
 * @property SaleDownpayment[] $saleDownpayments
 */
class BoardBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_board';
	}

	public function rules()
	{
		return array(
			array('name, position', 'required'),
			array('is_inactive', 'numerical', 'integerOnly'=>true),
			array('name, position', 'length', 'max'=>60),
			// The following rule is used by search().
			array('id, name, position, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'saleDownpayments' => array(self::HAS_MANY, 'SaleDownpayment', 'board_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'position' => 'Position',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('position', $this->position, true);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}