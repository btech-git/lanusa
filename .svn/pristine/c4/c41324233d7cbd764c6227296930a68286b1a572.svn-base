<?php

/**
 * @property integer $id
 * @property string $company
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $note
 * @property integer $branch_id
 * @property integer $account_id
 * @property integer $is_inactive
 *
 * @property PurchaseHeader[] $purchaseHeaders
 * @property PurchaseReceiptHeader[] $purchaseReceiptHeaders
 * @property Account $account
 * @property Branch $branch
 */
class SupplierBase extends ActiveRecord
{
	//attribute for filter
	public $branchName;
	
	public function tableName()
	{
		return 'tblla_supplier';
	}

	public function rules()
	{
		return array(
			array('company, name, branch_id', 'required'),
			array('email', 'email'),
			array('branch_id, account_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('company, name, phone, fax, email, website', 'length', 'max'=>60),
			array('address, note', 'safe'),
			// The following rule is used by search().
			array('id, company, name, address, phone, fax, email, website, note, branch_id, account_id, is_inactive,
				branchName
			', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'purchaseHeaders' => array(self::HAS_MANY, 'PurchaseHeader', 'supplier_id'),
			'purchaseReceiptHeaders' => array(self::HAS_MANY, 'PurchaseReceiptHeader', 'supplier_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company' => 'Company',
			'name' => 'Name',
			'address' => 'Address',
			'phone' => 'Phone',
			'fax' => 'Fax',
			'email' => 'Email',
			'website' => 'Website',
			'note' => 'Note',
			'branch_id' => 'Branch',
			'account_id' => 'Account',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.company', $this->company, true);
		$criteria->compare('t.name', $this->name, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('fax', $this->fax, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('website', $this->website, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.account_id', $this->account_id);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		$criteria->with = array('branch:resetScope');
		
		$criteria->compare('branch.name', $this->branchName, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}