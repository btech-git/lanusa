<?php

class AccountReceivableController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'report') {
            if (!(Yii::app()->user->checkAccess('deliveryReport')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

//	public function actionSummary()
//	{
//		$account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
//		$journalAccounting = Search::bind(new JournalAccounting('search'), isset($_GET['jouornalAccounting'])? $_GET['JounralAccounting'] : array());
//		
//		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
//		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
//		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
//		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
//		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
//
//		$number= (isset($_GET['Number'])) ? $_GET['Number'] : '';
//		$branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
//		
//		$accountReceivableSummary = new AccountReceivableSummary($account->searchByAccountReceivable(),$journalAccounting->search());
//		
//		$accountReceivableSummary->setupLoading($startDate, $endDate);
//		$accountReceivableSummary->setupPaging($pageSize, $currentPage);
//		$accountReceivableSummary->setupSorting();
//		$accountReceivableSummary->setupFilter($startDate, $endDate,$branchId);
//		$accountReceivableSummary->getSaldo();
//		
//		$this->render('summary', array(
//			'account' => $account,
//			'journalAccounting' => $journalAccounting,
//			'accountReceivableSummary' => $accountReceivableSummary,
//			'startDate' => $startDate,
//			'endDate' => $endDate,
//			'currentSort' => $currentSort,
//			'number' => $number,
//			'branchId' => $branchId,
//
//		));
//	}
//	
//	protected function reportGrandTotal($dataProvider)
//	{
//		$grandTotal = 0.00;
//
//		foreach ($dataProvider->data as $data)
//			$grandTotal += $data->amountPaid;
//
//		return $grandTotal;
//	}

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $agingPayableSummary = new AgingScheduleSummary($saleInvoice->search());
        $agingPayableSummary->setupLoading();
        $agingPayableSummary->setupPaging($pageSize, $currentPage);
        $agingPayableSummary->setupSorting();
        $agingPayableSummary->setupFilter($startDate, $endDate);
        $agingPayableSummary->setupBranch($branch);

        $this->render('summary', array(
            'saleInvoice' => $saleInvoice,
            'agingPayableSummary' => $agingPayableSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'listData' => $listData
        ));
    }

    public function reportTotalPayable($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->totalInvoice;

        return $grandTotal;
    }

    public function actionAjaxHtmlCustomer() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $customer = Customer::model()->findAllByAttributes(array(
                'branch_id' => $_POST['BranchId']
            ), array(
                'order' => 'name ASC'
            ));

            $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());

            $this->renderPartial('_customer', array(
                'customer' => $customer,
                'saleInvoice' => $saleInvoice
            ));
        }
    }
}