<?php

class PurchaseTaxSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
		$this->dataProvider->criteria->with = array(
			'branch:resetScope',
			'purchaseHeader:resetScope' => array(
				'with' => array(
					'supplier:resetScope'
				)
			)
		);
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
		$this->dataProvider->criteria->order = array('t.date');
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
		$this->dataProvider->sort->attributes = array('t.date', 'supplier.company');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter(array $filters)
	{
		$startDate = (empty($filters['startDate'])) ? date('Y-m-d') : $filters['startDate'];
		$endDate = (empty($filters['endDate'])) ? date('Y-m-d') : $filters['endDate'];
		
		$this->dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
		$this->dataProvider->criteria->compare('purchaseHeader.supplier_id', $filters['supplierId']);
		$this->dataProvider->criteria->compare('t.branch_id', $filters['branchId']);
		$this->dataProvider->criteria->compare('t.reference', $filters['reference']);
		$this->dataProvider->criteria->compare('t.supplier_tax_number', $filters['taxNumber']);
	}
}
