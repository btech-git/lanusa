<?php

/**
 * @property integer $id
 * @property string $bank
 * @property string $cheque_number
 * @property string $amount
 * @property integer $sale_cheque_header_id
 * @property integer $sale_receipt_header_id
 * @property integer $is_inactive
 *
 * @property SaleReceiptHeader $saleReceiptHeader
 * @property SaleChequeHeader $saleChequeHeader
 */
class SaleChequeDetailBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_sale_cheque_detail';
	}

	public function rules()
	{
		return array(
			array('sale_cheque_header_id, sale_receipt_header_id', 'required'),
			array('sale_cheque_header_id, sale_receipt_header_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('bank, cheque_number', 'length', 'max'=>60),
			array('amount', 'length', 'max'=>18),
			// The following rule is used by search().
			array('id, bank, cheque_number, amount, sale_cheque_header_id, sale_receipt_header_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'saleReceiptHeader' => array(self::BELONGS_TO, 'SaleReceiptHeader', 'sale_receipt_header_id'),
			'saleChequeHeader' => array(self::BELONGS_TO, 'SaleChequeHeader', 'sale_cheque_header_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'bank' => 'Bank',
			'cheque_number' => 'Cheque Number',
			'amount' => 'Amount',
			'sale_cheque_header_id' => 'Sale Cheque Header',
			'sale_receipt_header_id' => 'Sale Receipt Header',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('bank', $this->bank, true);
		$criteria->compare('cheque_number', $this->cheque_number, true);
		$criteria->compare('amount', $this->amount, true);
		$criteria->compare('sale_cheque_header_id', $this->sale_cheque_header_id);
		$criteria->compare('sale_receipt_header_id', $this->sale_receipt_header_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}