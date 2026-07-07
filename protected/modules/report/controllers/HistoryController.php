<?php

class HistoryController extends SelectionController
{
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$history = Search::bind(new History('search'), isset($_GET['History']) ? $_GET['History'] : array());
		$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

		$dataProvider = $history->search();
//		$dataProvider->criteria->with = array('customer');

		$page = array('size' => $pageSize, 'current' => $currentPage);
		//$date = array('attribute' => 'date', 'start' => $startDate, 'end' => $endDate);

		$sort = new CSort(get_class($history));
		$sort->attributes = array('time');

		$dataProvider = ReportHelper::finalizeDataProvider($dataProvider, $page, $sort);

		$this->render('summary', array(
			'history' => $history,
			'branch' => $branch,
			'dataProvider' => $dataProvider,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'sort' => $sort,
			'currentSort' => $currentSort,
		));
	}
}
