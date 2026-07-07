<?php

class PurchaseReturnDetail extends PurchaseReturnDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getTotal($receiveId = null)
	{
		return $this->quantity * $this->getUnitPrice($receiveId);
	}

	public function getUnitPrice($receiveId = null)
	{
		$receiveHeader = ReceiveHeader::model()->findByPk($receiveId);
		$purchaseDetail = PurchaseDetail::model()->findByAttributes(array(
			'purchase_header_id' => ($receiveHeader === null) ? $this->purchaseReturnHeader(array('scopes'=>'resetScope'))->receiveHeader(array('scopes'=>'resetScope'))->purchase_header_id : $receiveHeader->purchase_header_id,
			'product_id' => $this->product_id,
		));

		return ($purchaseDetail === null) ? 0.00 : $purchaseDetail->unit_price;
	}

	public function getQuantityReceived($receiveId = null)
	{
		if ($this->isNewRecord)
			$returnSql = '';
		else
			$returnSql = 'AND h.id <> :return_id';

		$sql = SqlViewGenerator::quantityReceive() ."
				INNER JOIN " . Product::model()->tableName() . " product
				ON receive.product_id = product.id
				WHERE receive.id = :receive_header_id AND receive.product_id = :product_id 
				AND NOT (returned.product_id IS NOT NULL AND returned.receive_header_id IS NULL)
				HAVING quantity_received > 0";

		$params = array(
			':receive_header_id' => ($receiveId === null) ? $this->purchaseReturnHeader->receive_header_id : $receiveId,
			':product_id' => $this->product_id,
		);

		if (!$this->isNewRecord)
			$params['return_id'] = $this->purchaseReturnHeader->id;

		$value = CActiveRecord::$db->createCommand($sql)->queryScalar($params);

		return ($value === false) ? 0.00 : $value;
	}
}