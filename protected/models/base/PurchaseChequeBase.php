<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $issue_date
 * @property string $due_date
 * @property string $cheque_number
 * @property string $amount
 * @property string $note
 * @property integer $purchase_receipt_header_id
 * @property integer $account_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property Account $account
 * @property PurchaseReceiptHeader $purchaseReceiptHeader
 * @property Admin $admin
 * @property Branch $branch
 */
class PurchaseChequeBase extends MonthlyTransactionActiveRecord
{
	public function tableName()
	{
		return 'tblla_purchase_cheque';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_month, cn_year, issue_date, due_date, cheque_number, purchase_receipt_header_id, account_id, branch_id, admin_id', 'required'),
			array('cn_ordinal, cn_month, cn_year, purchase_receipt_header_id, account_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('cheque_number', 'length', 'max'=>60),
			array('amount', 'length', 'max'=>18),
			array('note', 'safe'),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_month, cn_year, issue_date, due_date, cheque_number, amount, note, purchase_receipt_header_id, account_id, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'purchaseReceiptHeader' => array(self::BELONGS_TO, 'PurchaseReceiptHeader', 'purchase_receipt_header_id'),
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
			'issue_date' => 'Issue Date',
			'due_date' => 'Due Date',
			'cheque_number' => 'Cheque Number',
			'amount' => 'Amount',
			'note' => 'Note',
			'purchase_receipt_header_id' => 'Purchase Receipt Header',
			'account_id' => 'Account',
			'branch_id' => 'Branch',
			'admin_id' => 'Admin',
			'is_non_tax' => 'Is Non Tax',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('issue_date', $this->issue_date, true);
		$criteria->compare('due_date', $this->due_date, true);
		$criteria->compare('cheque_number', $this->cheque_number, true);
		$criteria->compare('amount', $this->amount, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('purchase_receipt_header_id', $this->purchase_receipt_header_id);
		$criteria->compare('t.account_id', $this->account_id);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('is_non_tax', $this->is_non_tax);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}