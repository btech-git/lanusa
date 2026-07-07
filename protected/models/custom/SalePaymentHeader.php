<?php

class SalePaymentHeader extends SalePaymentHeaderBase
{
	const CN_CONSTANT = 'SPY';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotalSale()
	{
		return $total = ($this->saleReceiptHeader === null) ? 0.00 : $this->saleReceiptHeader->totalInvoice;
	}
	
	public function getPayment()
	{
		$payment = ($this->saleReceiptHeader === null) ? 0.00 : $this->saleReceiptHeader->payment;

		foreach ($this->salePaymentDetails as $detail)
			$payment += $detail->amount;

		return $payment;
	}

	public function getRemaining()
	{
		return $this->totalSale - $this->payment;
	}

	public function getAmountPaid()
	{
		$total = 0.00;

		foreach ($this->salePaymentDetails as $detail)
			$total += $detail->amount;

		return $total;
	}
        
        public function searchWithPaging()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('t.date', $this->date, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('sale_receipt_header_id', $this->sale_receipt_header_id);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('admin_id', $this->admin_id);
		$criteria->compare('is_non_tax', $this->is_non_tax);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->user->getState( 'pageSize', Yii::app()->params[ 'defaultPageSize' ] ),
			),
		));
	}
}