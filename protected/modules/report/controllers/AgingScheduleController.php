<?php

class AgingScheduleController extends Controller
{
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$invoiceHeader = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());
		//$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

		$customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';

		$agingScheduleSummary = new AgingScheduleSummary($invoiceHeader->search());
		$agingScheduleSummary->setupLoading();
		$agingScheduleSummary->setupPaging($pageSize, $currentPage);
		$agingScheduleSummary->setupSorting();
		$agingScheduleSummary->setupFilter($startDate, $endDate, $customerId);
		$agingScheduleSummary->setupBranch($branch);
		
		$this->render('summary', array(
			'invoiceHeader' => $invoiceHeader,
			'agingScheduleSummary' => $agingScheduleSummary,
			'branch' => $branch,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'customerId' => $customerId,
			'listData' => $listData
		));
	}
	
	public function reportTotalReceivable($dataProvider)
	{
		$grandTotal = 0.00;

		foreach ($dataProvider->data as $data)
			$grandTotal += $data->totalInvoice;

		return $grandTotal;
	}
	
	public function actionAjaxHtmlCustomer()									//find customer based on selected branch
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$customer = Customer::model()->findAllByAttributes(
				array(
					'branch_id' => $_POST['BranchId']
				),
				array(
					'order' => 'name ASC'
				)
			);
			
			$customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';
			
			$this->renderPartial('_customer', array(
				'customer' => $customer,
				'customerId' => $customerId
			));
		}
	}
}
