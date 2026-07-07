<?php

class PurchaseReturnHeader extends PurchaseReturnHeaderBase
{
	const CN_CONSTANT = 'PRE';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getSubTotal()
	{
		$total = 0.00;

		foreach ($this->purchaseReturnDetails as $detail)
			$total += $detail->total;

		return $total;
	}
	
	public function getCalculatedTax()
	{
		return ($this->subTotal) * ($this->tax / 100);
	}
	
	public function getGrandTotal()
	{
		return $this->subTotal + $this->calculatedTax + $this->shipping_fee;
	}
        
        public function searchWithPaging()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('t.date', $this->date, true);
		$criteria->compare('tax', $this->tax);
		$criteria->compare('shipping_fee', $this->shipping_fee, true);
		$criteria->compare('t.note', $this->note, true);
		$criteria->compare('receive_header_id', $this->receive_header_id);
		$criteria->compare('t.warehouse_id', $this->warehouse_id);
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