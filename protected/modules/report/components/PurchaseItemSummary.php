<?php

class PurchaseItemSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
		$this->dataProvider->criteria->with = array(
			'category:resetScope',
            'purchaseDetails',
		);
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
//		$this->dataProvider->criteria->compare('purchaseDetails.is_inactive', 0);
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
	
	public function setupFilter(array $filters)
	{
		$startDate = (empty($filters['startDate'])) ? date('Y-m-d') : $filters['startDate'];
		$endDate = (empty($filters['endDate'])) ? date('Y-m-d') : $filters['endDate'];
		
		$this->dataProvider->criteria->compare('t.name', $filters['productName'], true);
		$this->dataProvider->criteria->compare('t.product_category_id', $filters['productCategoryId']);
		
		//check whether product is in purchase detail if purchase header is active and purchase details is active
		$this->dataProvider->criteria->addCondition('EXISTS (
			SELECT d.product_id
			FROM tblla_purchase_detail d
			INNER JOIN tblla_purchase_header h ON h.id = d.purchase_header_id
			WHERE d.product_id = t.id AND h.branch_id = :branch_id AND h.date BETWEEN :start_date AND :end_date
			AND d.is_inactive = 0 AND h.is_inactive = 0
		)');
		$this->dataProvider->criteria->params[':branch_id'] = $filters['branchId'];
		$this->dataProvider->criteria->params[':start_date'] = $startDate;
		$this->dataProvider->criteria->params[':end_date'] = $endDate;
	}
	
	public function reportGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
