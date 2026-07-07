<?php

class InventorySummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
        $this->dataProvider->criteria->together = true;
		$this->dataProvider->criteria->with = array(
			'category:resetScope',
            'inventories' => array(
				'order' => 'inventories.date ASC', 
			),
		);
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
	}
	
	public function setupPaging($pageSize, $currentPage)
	{
		$pageSize = (empty($pageSize)) ? 100000 : $pageSize;
		$pageSize = ($pageSize <= 0) ? 1 : $pageSize;
		$this->dataProvider->pagination->pageSize = $pageSize;
		
		$currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
		$this->dataProvider->pagination->currentPage = $currentPage;
	}
	
	public function setupSorting()
	{
		$this->dataProvider->sort->attributes = array('t.name', 't.size');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($filters)
	{
		$startDate = (empty($filters['startDate'])) ? date('Y-m-d') : $filters['startDate'];
		$endDate = (empty($filters['endDate'])) ? date('Y-m-d') : $filters['endDate'];
		
		$this->dataProvider->criteria->addBetweenCondition('inventories.date', $startDate, $endDate);
		
		$this->dataProvider->criteria->compare('t.name', $filters['productName'], TRUE);
		$this->dataProvider->criteria->compare('t.size', $filters['productSize'], TRUE);
		$this->dataProvider->criteria->compare('t.category_id', $filters['productCategory']);
		$this->dataProvider->criteria->compare('inventories.warehouse_id', $filters['warehouseId']);
	}
}