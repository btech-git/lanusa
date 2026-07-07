<?php

class SaleChequeHeader extends SaleChequeHeaderBase
{
	const CN_CONSTANT = 'SCH';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotalAmount()											//total for view, since the header has details
	{
		$total = 0;
		foreach ($this->saleChequeDetails as $saleChequeDetail)
		{
			$total += $saleChequeDetail->amount;
		}
		
		return $total;
	}
	
	public function getTotalSaleReceipt()
	{
		$total = 0;
		foreach ($this->saleChequeDetails as $detail)
		{
			$total += $detail->saleReceiptHeader->getTotalInvoice();
		}
		
		return $total;
	}
        
        public function searchWithPaging()
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
			'pagination' => array(
				'pageSize' => Yii::app()->user->getState( 'pageSize', Yii::app()->params[ 'defaultPageSize' ] ),
			),
		));
	}
}