<?php

class StockSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading($startDate, $endDate)
	{
		$this->dataProvider->criteria->with = array(
			'category:resetScope',
			'inventories',
                        'branch:resetScope',
                    
		);
		
		$this->dataProvider->criteria->with = array(
			'inventories' => array(
				'condition' => "inventories.date BETWEEN :startDate AND :endDate", 
				'params' => array(':startDate' => $startDate, ':endDate' => $endDate),
			),
			'category:resetScope',
		);
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
	}
	
	public function setupPaging($pageSize, $currentPage)
	{
		$pageSize = (empty($pageSize)) ? 10 : $pageSize;
		$pageSize = ($pageSize <= 0) ? 1 : $pageSize;
		$this->dataProvider->pagination->pageSize = $pageSize;
		
		$currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
		$this->dataProvider->pagination->currentPage = $currentPage;
	}
	
	public function setupSorting()
	{
		$this->dataProvider->sort->attributes = array('t.name', 'category.name');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($startDate, $endDate, $categoryId)
	{
		$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
		$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
		
		$this->dataProvider->criteria->with = array(
			'inventories' => array(
				'condition' => "inventories.date BETWEEN :startDate AND :endDate", 
				'params' => array(':startDate' => $startDate, ':endDate' => $endDate),
			),
			'category:resetScope',
		);
		
		$this->dataProvider->criteria->compare('t.category_id', $categoryId);
	}
	
	public function setupBranch($branch)
	{		
		$this->dataProvider->criteria->compare('branch_id', $branch);
	}
}
