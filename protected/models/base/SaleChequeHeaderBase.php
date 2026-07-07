<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $receive_date
 * @property string $due_date
 * @property string $note
 * @property integer $customer_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property SaleChequeDetail[] $saleChequeDetails
 * @property Customer $customer
 * @property Admin $admin
 * @property Branch $branch
 */
class SaleChequeHeaderBase extends MonthlyTransactionActiveRecord
{
	public function tableName()
	{
		return 'tblla_sale_cheque_header';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_month, cn_year, receive_date, due_date, customer_id, branch_id, admin_id', 'required'),
			array('cn_ordinal, cn_month, cn_year, customer_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('note', 'safe'),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_month, cn_year, receive_date, due_date, note, customer_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'saleChequeDetails' => array(self::HAS_MANY, 'SaleChequeDetail', 'sale_cheque_header_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
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
			'receive_date' => 'Receive Date',
			'due_date' => 'Due Date',
			'note' => 'Note',
			'customer_id' => 'Customer',
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
		$criteria->compare('t.receive_date', $this->receive_date, true);
		$criteria->compare('t.due_date', $this->due_date, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('t.customer_id', $this->customer_id);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('is_non_tax', $this->is_non_tax);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}