<?php

class Inventory extends InventoryBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getEndingQuantity($reportCurrentStock)
	{
		return $reportCurrentStock + $this->quantity_in - $this->quantity_out;
	}
	
	public function getEndingPrice($currentStock)
	{
		return $currentStock * $this->price;
	}
}