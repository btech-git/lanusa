<?php

/**
 * @property integer $id
 * @property string $number
 * @property string $name
 * @property integer $is_inactive
 */
class BankBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_bank';
	}

	public function rules()
	{
		return array(
			array('number, name', 'required'),
			array('is_inactive', 'numerical', 'integerOnly'=>true),
			array('number, name', 'length', 'max'=>60),
			// The following rule is used by search().
			array('id, number, name, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'Number',
			'name' => 'Name',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('number', $this->number, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}