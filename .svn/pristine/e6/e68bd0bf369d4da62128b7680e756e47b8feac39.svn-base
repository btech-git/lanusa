<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $account_category_type_id
 * @property integer $is_inactive
 *
 * @property Account[] $accounts
 * @property AccountCategoryType $accountCategoryType
 */
class AccountCategoryBase extends ActiveRecord
{

	public function tableName()
	{
		return 'tblla_account_category';
	}

	public function rules()
	{
		return array(
			array('name, account_category_type_id', 'required'),
			array('account_category_type_id, is_inactive', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 60),
			// The following rule is used by search().
			array('id, name, account_category_type_id, is_inactive', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'accounts' => array(self::HAS_MANY, 'Account', 'account_category_id'),
			'accountCategoryType' => array(self::BELONGS_TO, 'AccountCategoryType', 'account_category_type_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'account_category_type_id' => 'Account Category Type',
			'is_inactive' => 'Status',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('account_category_type_id', $this->account_category_type_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
	}
}