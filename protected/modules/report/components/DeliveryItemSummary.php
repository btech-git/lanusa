<?php

class DeliveryItemSummary extends CComponent
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
            'deliveryDetails' => array(
                'with' => array(
                    'deliveryHeader'
                )
            )
		);
        
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
		$this->dataProvider->criteria->compare('deliveryDetails.is_inactive', 0);
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
		$this->dataProvider->sort->attributes = array('name', 'category.name');
		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
	}
	
	public function setupFilter($filters)
	{
		$startDate = (empty($filters['startDate'])) ? date('Y-m-d') : $filters['startDate'];
		$endDate = (empty($filters['endDate'])) ? date('Y-m-d') : $filters['endDate'];
		
		$this->dataProvider->criteria->addBetweenCondition('deliveryHeader.date', $startDate, $endDate);
		$this->dataProvider->criteria->compare('deliveryHeader.branch_id', $filters['branch']);

	}
	
	public function getGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
