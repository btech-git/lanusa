<?php

class AgingPayableController extends Controller
{
    public function filters() {
        return array(
//            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('stockAdjustmentReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$purchaseInvoice = Search::bind(new PurchaseInvoiceHeader('search'), isset($_GET['PurchaseInvoiceHeader']) ? $_GET['PurchaseInvoiceHeader'] : array());
		//$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
		$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

//		$supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';

		$agingPayableSummary = new AgingPayableSummary($purchaseInvoice->search());
		$agingPayableSummary->setupLoading();
		$agingPayableSummary->setupPaging($pageSize, $currentPage);
		$agingPayableSummary->setupSorting();
		$agingPayableSummary->setupFilter($startDate, $endDate);
		$agingPayableSummary->setupBranch($branch);
		
		$this->render('summary', array(
			'purchaseInvoice' => $purchaseInvoice,
			'agingPayableSummary' => $agingPayableSummary,
			'branch' => $branch,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'listData' => $listData
		));
	}
	
	public function reportTotalPayable($dataProvider)
	{
		$grandTotal = 0.00;

		foreach ($dataProvider->data as $data)
			$grandTotal += $data->totalPurchase;

		return $grandTotal;
	}
	
	public function actionAjaxHtmlSupplier()									//find supplier based on selected branch
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$supplier = Supplier::model()->findAllByAttributes(
				array(
					'branch_id' => $_POST['BranchId']
				),
				array(
					'order' => 'name ASC'
				)
			);
			
			$purchaseInvoice = Search::bind(new PurchaseInvoiceHeader('search'), isset($_GET['PurchaseInvoiceHeader']) ? $_GET['PurchaseInvoiceHeader'] : array());

			$this->renderPartial('_supplier', array(
				'supplier' => $supplier,
				'purchaseInvoice' => $purchaseInvoice
			));
		}
	}
}
