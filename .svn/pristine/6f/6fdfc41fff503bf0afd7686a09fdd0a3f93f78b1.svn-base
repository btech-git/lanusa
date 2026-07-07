<?php

class SaleDownpayment extends SaleDownpaymentBase
{
	const CN_CONSTANT = 'SDP';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
        public function getSubTotal()
	{
		return $this->amount;
	}
	
	public function getCalculatedTax()
	{
		return $this->amount * .1;
	}
	
	public function getCalculatedTax1()
	{
		return $this->amount * $this->tax / 100;
	}
	
	public function getGrandTotal()
	{
		return $this->amount+$this->calculatedTax1;
	}
        
        public function searchWithPaging()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('t.date', $this->date, true);
		$criteria->compare('t.quantity', $this->quantity);
		$criteria->compare('t.amount', $this->amount, true);
		$criteria->compare('t.tax', $this->tax);
		$criteria->compare('t.note', $this->note, true);
		$criteria->compare('t.tax_number', $this->tax_number, true);
		$criteria->compare('t.customer_id', $this->customer_id);
		$criteria->compare('t.board_id', $this->board_id);
		$criteria->compare('t.account_id', $this->account_id);
		$criteria->compare('t.branch_id', $this->branch_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('t.is_non_tax', $this->is_non_tax);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->user->getState( 'pageSize', Yii::app()->params[ 'defaultPageSize' ] ),
			),
		));
	}
}
