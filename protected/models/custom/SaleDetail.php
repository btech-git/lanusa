<?php

class SaleDetail extends SaleDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotal()
	{
		return $this->quantity * $this->unit_price;
	}
	
	public function getCurrentStock($warehouseId = null)
	{
		$sql = SqlGenerator::localStock();

		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
			':product_id' => $this->product_id,
			':warehouse_id' => ($warehouseId !== null) ? $warehouseId : $this->deliveryHeader->warehouse_id,
		));

		return ($value === false) ? 0 : $value;
	}
}