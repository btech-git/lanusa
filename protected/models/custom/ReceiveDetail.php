<?php

class ReceiveDetail extends ReceiveDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getQuantityOrdered($purchaseHeaderId = null)
	{
		$sql = "SELECT purchase.quantity - COALESCE(receive.quantity_receive, 0) AS quantity_ordered
				FROM
				(
					SELECT h.id, d.quantity, d.product_id
					FROM ".PurchaseHeader::model()->tableName()." h
					INNER JOIN ".PurchaseDetail::model()->tableName()." d ON h.id = d.purchase_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
				) purchase
				LEFT OUTER JOIN
				(
					SELECT h.purchase_header_id, SUM(COALESCE(d.quantity, 0)) AS quantity_receive, d.product_id
					FROM ".ReceiveHeader::model()->tableName()." h
					INNER JOIN ".ReceiveDetail::model()->tableName()." d ON h.id = d.receive_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY h.purchase_header_id, d.product_id
				) receive
				ON purchase.id = receive.purchase_header_id
				AND purchase.product_id = receive.product_id
				WHERE purchase.id = :purchase_id AND purchase.product_id =:product_id 
				AND purchase.quantity - COALESCE(receive.quantity_receive, 0) > 0";

		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(':purchase_id' => $purchaseHeaderId, ':product_id' => $this->product_id));
		
		return ($value === false) ? 0 : $value;
	}
	
	public function getUnitPrice()
	{
        return ($this->purchaseDetail === null) ? 0.00 : $this->purchaseDetail->unit_price * (1 + $this->purchaseDetail->purchaseHeader->tax / 100);
	}
	
	public function getTotalReporting()
	{
        if ((int)$this->is_inactive === 0)
            return $this->quantity * $this->unitPrice;
	}
	
	public function getTotal()
	{
        $unitPrice = empty($this->purchase_detail_id) ? 0.00 : $this->purchaseDetail->unit_price;
        
        if ((int)$this->is_inactive === 0)
            return $this->quantity * $unitPrice;
	}
	
//	public function getDiscount()
//	{
//		if ($this->isNewRecord)
//			return $this->discount;
//		else
//		{
//			$purchaseDetail = PurchaseDetail::model()->findByAttributes(array(
//				'purchase_header_id' => $this->receiveHeader->purchase_header_id,
//				'product_id' => $this->product_id,
//			));
//
//			return ($purchaseDetail === null) ? 0.00 : $purchaseDetail->discount;
//		}
//	}
}