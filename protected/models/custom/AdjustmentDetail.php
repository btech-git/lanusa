<?php

class AdjustmentDetail extends AdjustmentDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
        
	public function getCurrentStock($warehouseId = null)
	{
		$sql = SqlGenerator::localStock();

		$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
			':product_id' => $this->product_id,
			':warehouse_id' => ($warehouseId !== null) ? $warehouseId : $this->adjustmentHeader->warehouse_id,
			));

		return ($value === false) ? 0 : $value;
	}
	
	public function getQuantityDifference($warehouseId = null)
	{
		return $this->quantity_adjustment - $this->getCurrentStock($warehouseId);
	}
}