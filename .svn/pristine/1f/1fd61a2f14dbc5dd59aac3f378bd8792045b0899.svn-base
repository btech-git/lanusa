<?php

class SaleCustomerController extends Controller
{
	public function filters()
	{
		return array(
			'access',
		);
	}
	
	public function filterAccess($filterChain)
    {
        if ($filterChain->action->id === 'summary' )
        {
            if (!(Yii::app()->user->checkAccess('saleReport') ))
                $this->redirect(array('/site/login'));
        }
  
        $filterChain->run();
    }
	
	
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$customer = Search::bind(new Customer('search'), isset($_GET['Customer']) ? $_GET['Customer'] : array());
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
		
		$customerName = (isset($_GET['Customer']['name'])) ? $_GET['Customer']['name'] : '';
		
		$customerSelected = Customer::model()->findByAttributes(array('name' => $customerName));

		$saleCustomerSummary = new SaleCustomerSummary($customer->customerBySaleCustomerSearch($customerSelected, $branch, $startDate,$endDate));
		$saleCustomerSummary->setupLoading();
		$saleCustomerSummary->setupPaging($pageSize, $currentPage);
		$saleCustomerSummary->setupSorting();
		$saleCustomerSummary->setupFilter($startDate, $endDate, $branch, $customerName);

		$this->render('summary', array(
			'customer' => $customer,
			'saleCustomerSummary' => $saleCustomerSummary,
			'branch' => $branch,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'listData' => $listData,
		
		));
	}
}
