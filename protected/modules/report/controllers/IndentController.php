<?php

class IndentController extends SelectionController
{
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$indentHeader = Search::bind(new IndentHeader('search'), isset($_GET['IndentHeader']) ? $_GET['IndentHeader'] : array());
		//$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

		$indentSummary = new IndentSummary($indentHeader->search());
		$indentSummary->setupLoading();
		$indentSummary->setupPaging($pageSize, $currentPage);
		$indentSummary->setupSorting();
		$indentSummary->setupFilter($startDate, $endDate);
		$indentSummary->setupBranch($branch);
		
		$this->render('summary', array(
			'indentHeader' => $indentHeader,
			'indentSummary' => $indentSummary,
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
			$grandTotal += $data->grandTotal;

		return $grandTotal;
	}
}
