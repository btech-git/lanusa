<?php

class PurchaseChequeController extends Controller
{
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$purchaseCheque = Search::bind(new PurchaseCheque('search'), isset($_GET['PurchaseCheque']) ? $_GET['PurchaseCheque'] : array());
		
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
		$supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';
		
		$purchaseChequeSummary = new PurchaseChequeSummary($purchaseCheque->search());
		$purchaseChequeSummary->setupLoading();
		$purchaseChequeSummary->setupPaging($pageSize, $currentPage);
		$purchaseChequeSummary->setupSorting();
		$purchaseChequeSummary->setupFilter($startDate, $endDate, $supplierId);
		$purchaseChequeSummary->setupBranch($branch);
		
		$this->render('summary', array(
			'purchaseCheque' => $purchaseCheque,
			'purchaseChequeSummary' => $purchaseChequeSummary,
			'branch' => $branch,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'supplierId' => $supplierId,
			'listData' => $listData
		));
	}
}
