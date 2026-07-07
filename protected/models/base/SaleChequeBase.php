<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $receive_date
 * @property string $due_date
 * @property string $cheque_number
 * @property string $note
 * @property string $amount
 * @property integer $sale_receipt_header_id
 * @property string $bank
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property Admin $admin
 * @property SaleReceiptHeader $saleReceiptHeader
 * @property Branch $branch
 */
class SaleChequeBase extends MonthlyTransactionActiveRecord
{
	public function tableName()
	{
		return 'tblla_sale_cheque';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_month, cn_year, receive_date, due_date, cheque_number, sale_receipt_header_id, bank, branch_id, admin_id', 'required'),
			array('cn_ordinal, cn_month, cn_year, sale_receipt_header_id, branch_id, admin_id, is_non_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('cheque_number, bank', 'length', 'max'=>60),
			array('amount', 'length', 'max'=>18),
			array('note', 'safe'),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_month, cn_year, receive_date, due_date, cheque_number, note, amount, sale_receipt_header_id, bank, branch_id, admin_id, is_non_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'saleReceiptHeader' => array(self::BELONGS_TO, 'SaleReceiptHeader', 'sale_receipt_header_id'),
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
			'cheque_number' => 'Cheque Number',
			'note' => 'Note',
			'amount' => 'Amount',
			'sale_receipt_header_id' => 'Sale Receipt Header',
			'bank' => 'Bank',
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
		$criteria->compare('t.cheque_number', $this->cheque_number, true);
		$criteria->compare('t.note', $this->note, true);
		$criteria->compare('t.amount', $this->amount, true);
		$criteria->compare('t.sale_receipt_header_id', $this->sale_receipt_header_id);
		$criteria->compare('t.bank', $this->bank, true);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('t.is_non_tax', $this->is_non_tax);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}