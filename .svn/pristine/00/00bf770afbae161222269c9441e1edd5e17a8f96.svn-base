<?php

class DeliveryDetail extends DeliveryDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getProductName($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) 
				? $this->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id 
				: $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->product_name;
	}
    
	public function getProductUnit($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id : $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->unit->name;
	}
     
	public function getUnitPrice($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id : $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->unit_price;
	}
     
	public function getDiscountSale($saleHeaderId = null)
	{
		$saleDetail = SaleDetail::model()->findByAttributes(array(
			'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes'=>'resetScope'))->sale_header_id : $saleHeaderId,
			'product_id' => $this->product_id,
		));
		
		return ($saleDetail === null) ? 0.00 : $saleDetail->discount;
	}
	
	public function getTotal($saleHeaderId = null)
	{
		return $this->quantity * $this->getUnitPrice($saleHeaderId) * (1 - ($this->getDiscountSale($saleHeaderId) / 100));
	}
     
	public function getQuantityOrdered($saleHeaderId = null)
	{
//		$sql = "SELECT sale.quantity - COALESCE(delivery.quantity_delivery, 0) AS quantity_sale
//				FROM
//				(
//					SELECT h.id, d.quantity, d.product_id
//					FROM ".  SaleHeader::model()->tableName()." h
//					INNER JOIN ".SaleDetail::model()->tableName()." d ON h.id = d.sale_header_id
//					WHERE h.is_inactive = 0 AND d.is_inactive = 0
//				) sale
//				LEFT OUTER JOIN
//				(
//					SELECT h.sale_header_id, SUM(COALESCE(d.quantity, 0)) AS quantity_delivery, d.product_id
//					FROM ".  DeliveryHeader::model()->tableName()." h
//					INNER JOIN ".DeliveryDetail::model()->tableName()." d ON h.id = d.delivery_header_id
//					WHERE h.is_inactive = 0 AND d.is_inactive = 0
//					GROUP BY h.sale_header_id, d.product_id
//				) delivery
//				ON sale.id = delivery.sale_header_id
//				AND sale.product_id = delivery.product_id
//				WHERE sale.id = :sale_id AND sale.product_id =:product_id 
//				AND sale.quantity - COALESCE(delivery.quantity_delivery, 0) > 0";

		$sql = "SELECT p.quantity - SUM(COALESCE(r.quantity, 0)) AS quantity_sale
				FROM " . SaleDetail::model()->tableName() . " p
				LEFT OUTER JOIN " . DeliveryDetail::model()->tableName() . " r
				ON p.id = r.sale_detail_id AND p.product_id = r.product_id AND r.is_inactive = 0 AND p.is_inactive = 0
				WHERE p.sale_header_id = :sale_header_id AND p.product_id = :product_id
				GROUP BY p.id
				HAVING quantity_sale > 0";
		
		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(':sale_header_id' => $saleHeaderId, ':product_id' => $this->product_id));
		
		return ($value === false) ? 0 : $value;
	}
	
//	public function getCurrentStock($warehouseId = null)
//	{
//		$sql = SqlGenerator::localStock();
//
//		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
//			':product_id' => $this->product_id,
//			':warehouse_id' => ($warehouseId !== null) ? $warehouseId : $this->deliveryHeader->warehouse_id,
//		));
//
//		return ($value === false) ? 0 : $value;
//	}
//
//	public function getTotal()
//	{
//		return $this->quantity * $this->unit_price * (1 - ($this->discount / 100));
//	}
}