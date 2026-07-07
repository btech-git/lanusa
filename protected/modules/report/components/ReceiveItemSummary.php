<?php

class ReceiveItemSummary extends CComponent
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
            'receiveDetails' => array(
                'with' => array(
                    'receiveHeader'
                )
            )
		);
		
//		$this->dataProvider->criteria->join = '
//			JOIN tblla_receive_detail receiveDetails ON receiveDetails.product_id = t.id
//			JOIN tblla_receive_header receiveHeader ON receiveHeader.id = receiveDetails.receive_header_id
//		';
		
		$this->dataProvider->criteria->compare('t.is_inactive', 0);
		$this->dataProvider->criteria->compare('receiveDetails.is_inactive', 0);
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
		
//		$this->dataProvider->criteria->addCondition('EXISTS (
//			SELECT d.product_id
//			FROM tblla_receive_detail d
//			JOIN tblla_receive_header h ON h.id = d.receive_header_id
//			WHERE d.product_id = t.id AND h.branch_id = :branch_id AND h.date BETWEEN :start_date AND :end_date
//			AND d.is_inactive = 0 AND h.is_inactive = 0
//		)');
//		$this->dataProvider->criteria->params[':branch_id'] = $filters['branch'];
//		$this->dataProvider->criteria->params[':start_date'] = $startDate;
//		$this->dataProvider->criteria->params[':end_date'] = $endDate;
		
		$this->dataProvider->criteria->addBetweenCondition('receiveHeader.date', $startDate, $endDate);
		$this->dataProvider->criteria->compare('receiveHeader.branch_id', $filters['branch']);

	}
	
	public function getGrandTotal()
	{
		$grandTotal = 0.00;

		foreach ($this->dataProvider->data as $data)
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
