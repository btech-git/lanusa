<?php

class DeliveryPriceController extends SelectionController
{
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$deliveryHeader = Search::bind(new DeliveryHeader('search'), isset($_GET['DeliveryHeader']) ? $_GET['DeliveryHeader'] : array());
		//$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

		$deliveryPriceSummary = new DeliveryPriceSummary($deliveryHeader->search());
		$deliveryPriceSummary->setupLoading();
		$deliveryPriceSummary->setupPaging($pageSize, $currentPage);
		$deliveryPriceSummary->setupSorting();
		$deliveryPriceSummary->setupFilter($startDate, $endDate);
		$deliveryPriceSummary->setupBranch($branch);
		
		$this->render('summary', array(
			'deliveryHeader' => $deliveryHeader,
			'deliveryPriceSummary' => $deliveryPriceSummary,
			'branch' => $branch,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'listData' => $listData
		));
	}
	
	protected function reportGrandTotal($dataProvider)
	{
		$grandTotal = 0.00;

		foreach ($dataProvider->data as $data)
			$grandTotal += $data->saleHeader->grandTotal;

		return $grandTotal;
	}

}
?>
