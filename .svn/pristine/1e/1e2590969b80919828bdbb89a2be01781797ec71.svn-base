<?php

class TransferDetail extends TransferDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCurrentStock($warehouseId = false)
	{
		$sql = SqlGenerator::localStock();

		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
			':product_id' => $this->product_id,
			':warehouse_id' => ($warehouseId !== false) ? $warehouseId : $this->transferHeader->warehouse_id,
			));

		return ($value === false) ? 0 : $value;
	}
}