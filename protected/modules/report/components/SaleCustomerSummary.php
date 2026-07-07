<?php

class SaleCustomerSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
//		$dataProvider->criteria->with = array(
//			'saleHeaders' => array(
//				'with' => array('saleDetails, customer') ,
//			),
//		);
		
		$this->dataProvider->criteria->join = '
			JOIN tblla_sale_header saleHeaders ON saleHeaders.customer_id = t.id
			JOIN tblla_customer customer ON customer.id = saleHeaders.customer_id
		';
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
		$this->dataProvider->criteria->compare('saleHeaders.is_inactive', 0);
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
		$this->dataProvider->sort->attributes = array('name');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($startDate, $endDate, $branchId, $customerName)
	{
		$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
		$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
	
//		$this->dataProvider->criteria->with = array('saleHeaders' => array(
//			'with' => array('customer' => array(
//				'condition' => "date BETWEEN :startDate AND :endDate AND saleHeaders.branch_id = :branchId AND customer.name LIKE :customerName", 
//				'params' => array(':customerName' => "%$customerName%", ':startDate' => $startDate, ':endDate' => $endDate, ':branchId' => $branchId)
//			))
//		));
		
		$this->dataProvider->criteria->addCondition('
			date BETWEEN :startDate AND :endDate AND saleHeaders.branch_id = :branchId AND customer.name LIKE :customerName
		');
		$this->dataProvider->criteria->params[':customerName'] = "%$customerName%";
		$this->dataProvider->criteria->params[':startDate'] = $startDate;
		$this->dataProvider->criteria->params[':endDate'] = $endDate;
		$this->dataProvider->criteria->params[':branchId'] = $branchId;
	}
	
//	public function setupBranch($branch)
//	{		
//		$this->dataProvider->criteria->compare('t.branch_id', $branch);
//	}
	
	public function getGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
