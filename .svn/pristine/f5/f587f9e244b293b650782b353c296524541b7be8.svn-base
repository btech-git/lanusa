<?php

class SaleChequeSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
//		$this->dataProvider->criteria->join = '
//			JOIN `tblla_branch` branch ON branch.id = t.branch_id
//			JOIN `tblla_customer` customer ON customer.id = t.customer_id
//		';
                
                
                $this->dataProvider->criteria->with = array(
			'customer:resetScope',
                        'branch:resetScope',
		);
                
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
		
//		JOIN `tblla_sale_cheque_detail` saleChequeDetails ON saleChequeDetails.sale_cheque_header_id = t.id
//			JOIN `tblla_sale_receipt_header` saleReceiptHeader ON saleReceiptHeader.id = saleChequeDetails.sale_receipt_header_id
//			JOIN `tblla_customer` customer ON saleReceiptHeader.customer_id = customer.id
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
		$this->dataProvider->sort->attributes = array('t.receive_date', 'customer.company');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter(array $filters)
	{
		$startDate = (empty($filters['startDate'])) ? date('Y-m-d') : $filters['startDate'];
		$endDate = (empty($filters['endDate'])) ? date('Y-m-d') : $filters['endDate'];
		
		$this->dataProvider->criteria->addBetweenCondition('t.receive_date', $startDate, $endDate);
		$this->dataProvider->criteria->compare('t.customer_id', $filters['customerId']);
		$this->dataProvider->criteria->compare('t.branch_id', $filters['branchId']);
	}
}
