<?php

class AgingScheduleSummary extends CComponent
{
	public $dataProvider;
	
	public function __construct($dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}
	
	public function setupLoading()
	{
		$this->dataProvider->criteria->with = array(
		//$this->dataProvider->criteria->compare('deliveryHeader.customer_id', $customerId);
//		$this->dataProvider->criteria->join = "INNER JOIN " . DeliveryHeader::model()->tableName() . " deliveryHeader ON deliveryHeader.id = t.delivery_header_id INNER JOIN " . Customer::model()->tableName() . " customer ON deliveryHeader.customer_id = customer.id
//										LEFT OUTER JOIN ". SaleReceiptDetail::model()->tableName() ." saleReceiptDetail ON t.id = saleReceiptDetail.sale_invoice_id ";
//		$this->dataProvider->criteria->addCondition(SqlViewGenerator::agingReceivable());
			'saleReceiptDetails:resetScope'=>array(
				'with'=>array('saleReceiptHeader:resetScope'),
			),
			'deliveryHeader:resetScope'=>array(
				'with'=>array(
					'saleHeader'=>array(
						'with'=>'customer:resetScope',
					),
				),
			),
            'branch:resetScope',
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
		$this->dataProvider->sort->attributes = array('t.date', 'customer.company');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($startDate, $endDate)
	{
		$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
		$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
		$this->dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
//		$this->dataProvider->criteria->compare('customer.id', $customerId);
	}
	
	public function setupBranch($branch)
	{		
		$this->dataProvider->criteria->compare('t.branch_id', $branch);
	}
	
	public function getGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
