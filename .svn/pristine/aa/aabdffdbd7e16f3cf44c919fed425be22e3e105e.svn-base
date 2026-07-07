<?php

class Customer extends CustomerBase
{
	//attribute for filter
	public $branchName;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
    
    public function getTotalSales()
	{
		$totalSales= 0.00;
		
		foreach($this->saleHeaders as $saleHeader)
		{
			$totalSales += $saleHeader->grandTotal;
		}
		
		return $totalSales;
	}
	
	public function customerBySaleCustomerSearch($customerSelected, $branch, $startDate, $endDate)
	{
		
		$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
		$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
		
		$criteria = new CDbCriteria();
		
		if($customerSelected)
		{
			$criteria->condition = 't.id IN (
				SELECT customer_id 
				FROM tblla_sale_header 
				WHERE 
				customer_id = :customerId AND 
				branch_id = :branchId AND
				date BETWEEN :startDate AND :endDate
			)';
			$criteria->params = array(
				':customerId'=>$customerSelected->id, 
				':branchId' => $branch,
				':startDate' => $startDate,
				':endDate' => $endDate,
			);
		}
		else{
			$criteria->condition = 't.id IN (
				SELECT customer_id 
				FROM tblla_sale_header 
				WHERE branch_id = :branchId AND
				date BETWEEN :startDate AND :endDate
			)';
			$criteria->params = array(
				'branchId' => $branch,
				':startDate' => $startDate,
				':endDate' => $endDate,
			);
		
		}
		
		


//		$criteria->addCondition(
//			'EXISTS (
//				SELECT sh.customer_id
//				FROM `tblla_sale_header` sh
//				WHERE t.id = sh.customer_id
//				GROUP BY sh.customer_id)'
//		);
		
		return new CActiveDataProvider(new Customer(), array(
			'criteria' => $criteria
		));
	}
}