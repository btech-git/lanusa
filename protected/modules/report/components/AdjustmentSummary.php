<?php

class AdjustmentSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
		$this->dataProvider->criteria->with = array(
			'adjustmentDetails'=>array(
				'with'=>array('product:resetScope' => array(
					'with' => array(
						'unit:resetScope',
						'category:resetScope',
					),
				)),
			),
			'branch:resetScope',
			'warehouse:resetScope',
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
		$this->dataProvider->sort->attributes = array('date', 'warehouse_id');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($startDate, $endDate)
	{
		$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
		$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
		$this->dataProvider->criteria->addBetweenCondition('date', $startDate, $endDate);
	}
	
	public function setupBranch($branch)
	{		
		$this->dataProvider->criteria->compare('branch_id', $branch);
	}
	
	public function reportGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
