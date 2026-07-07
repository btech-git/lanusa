<?php

class PurchaseInvoiceHeader extends PurchaseInvoiceHeaderBase
{
	const CN_CONSTANT = 'PINV';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotalPurchase()
	{
		$total = 0.00;
		foreach ($this->purchaseInvoiceDetails as $detail)
			$total += $detail->purchaseHeader->grandTotal;

		return $total;
	}
	
	public function searchByPurchaseReceipt()
	{
		$criteria = new CDbCriteria;

		$criteria->condition = "t.id NOT IN (SELECT purchase_invoice_header_id FROM tblla_purchase_receipt_detail WHERE is_inactive = 0)";

		$criteria->compare('cn_ordinal', $this->cn_ordinal);
		$criteria->compare('cn_month', $this->cn_month);
		$criteria->compare('cn_year', $this->cn_year);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('supplier_id', $this->supplier_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

}