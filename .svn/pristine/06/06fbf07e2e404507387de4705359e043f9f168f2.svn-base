<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 *
 * @property AccountCategory[] $accountCategories
 */
class AccountCategoryTypeBase extends ActiveRecord
{

	public function tableName()
	{
		return 'tblla_account_category_type';
	}

	public function rules()
	{
		return array(
			array('name', 'required'),
			array('is_inactive', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 60),
			// The following rule is used by search().
			array('id, name, is_inactive', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'accountCategories' => array(self::HAS_MANY, 'AccountCategory', 'account_category_type_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'is_inactive' => 'Status',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
	}
}