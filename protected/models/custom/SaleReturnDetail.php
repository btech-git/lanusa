<?php

class SaleReturnDetail extends SaleReturnDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getProductName($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) ? $this->saleInvoice->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id : $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->product_name;
	}
    
	public function getProductUnit($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) ? $this->saleInvoice->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id : $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->unit->name;
	}
     
    public function getTotal($saleInvoiceId = null)
	{
		return $this->quantity * $this->getUnitPrice($saleInvoiceId);
	}

	public function getUnitPrice($saleInvoiceId = null)
	{
		$saleInvoice = SaleInvoice::model()->findByPk($saleInvoiceId);
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleInvoice === null) ? $this->saleReturnHeader->saleInvoice->deliveryHeader->sale_header_id : $saleInvoice->deliveryHeader->sale_header_id,
			'product_id' => $this->product_id,
		));

		return ($saleDetail === null) ? 0.00 : $saleDetail->unit_price;
	}

	public function getQuantitySold($saleInvoiceId = null)
	{
		if ($this->isNewRecord)
			$returnSql = '';
		else
			$returnSql = 'AND h.id <> :return_id';

		$sql = "SELECT delivery.quantity - COALESCE(returned.quantity, 0) AS quantity_sold
				FROM
				(
					(
						SELECT h.id, d.quantity, d.product_id
						FROM tblla_delivery_header h
						INNER JOIN tblla_delivery_detail d ON h.id = d.delivery_header_id
						WHERE h.is_inactive = 0 AND d.is_inactive = 0
					) delivery
					LEFT OUTER JOIN
					(
						SELECT h.id, h.delivery_header_id
						FROM tblla_sale_invoice h
						WHERE h.is_inactive = 0 
					) invoice
					ON delivery.id = invoice.delivery_header_id
					LEFT OUTER JOIN
					(
						SELECT h.sale_invoice_id, SUM(COALESCE(d.quantity, 0)) AS quantity, d.product_id
						FROM tblla_sale_return_header h
						INNER JOIN tblla_sale_return_detail d ON h.id = d.sale_return_header_id
						WHERE h.is_inactive = 0 AND d.is_inactive = 0 {$returnSql}
						GROUP BY h.sale_invoice_id, d.product_id
					) returned
					ON invoice.id = returned.sale_invoice_id
					AND delivery.product_id = returned.product_id
				)
				INNER JOIN tblla_product
				ON delivery.product_id = tblla_product.id
				WHERE invoice.id = :invoice_id AND delivery.product_id = :product_id 
				AND NOT (returned.product_id IS NOT NULL AND returned.sale_invoice_id IS NULL)";

		$params = array(
			':invoice_id' => ($saleInvoiceId === null) ? $this->saleReturnHeader->sale_invoice_id : $saleInvoiceId,
			':product_id' => $this->product_id,
		);
		
		if (!$this->isNewRecord)
			$params['return_id'] = $this->saleReturnHeader->id;
		
		$value = CActiveRecord::$db->createCommand($sql)->queryScalar($params);

		return ($value === false) ? 0.00 : $value;
	}
}