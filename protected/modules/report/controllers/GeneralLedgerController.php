<?php

class GeneralLedgerController extends Controller
{
	public function filters()
	{
		return array(
			'access',
		);
	}

	public function filterAccess($filterChain)
	{
		if ($filterChain->action->id === 'summary'
			|| $filterChain->action->id === 'ajaxHtmlAccount')
		{
			if (!(Yii::app()->user->checkAccess('allAccountingReport')))
				$this->redirect(array('/site/login'));
		}

		$filterChain->run();
	}
	
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
		$journalAccounting = Search::bind(new JournalAccounting('search'), isset($_GET['JournalAccounting'])? $_GET['JournalAccounting'] : array());
		$branch = Search::bind(new Branch('search'), isset($_GET['Branch']) ? $_GET['Branch'] : array());
		
		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

		$number= (isset($_GET['Number'])) ? $_GET['Number'] : '';
		$branchId = (isset($_GET['Branch']['id'])) ? $_GET['Branch']['id'] : '';

		$startAccount = (isset($_GET['StartAccount'])) ? $_GET['StartAccount'] : '';
		$endAccount = (isset($_GET['EndAccount'])) ? $_GET['EndAccount'] : '';
		
		$branchName = Branch::model()->findByPk($branchId);
		
		$accounts = Account::model()->findAllByAttributes(
			array(
				'branch_id' => $branchId
			),
			array(
				'order' => 'code ASC',
			)
		);
		
		$generalLedgerSummary = new GeneralLedgerSummary($account->search(),$journalAccounting->search());
		$generalLedgerSummary->setupLoading($startDate, $endDate,$startAccount, $endAccount);
		$generalLedgerSummary->setupPaging($pageSize, $currentPage);
		$generalLedgerSummary->setupSorting();
		$generalLedgerSummary->setupFilter($startDate, $endDate, $branchId, $startAccount, $endAccount);
		$generalLedgerSummary->getSaldo($startDate);
		
		//$beginningBalanceLedger = $generalLedgerSummary->beginningLedgerSummary($generalLedgerSummary->id,$startDate);
		
        if (isset($_GET['SaveExcel']))
			$this->saveToExcel($generalLedgerSummary, $branch, $generalLedgerSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));
		
                
		$this->render('summary', array(
			'account' => $account,
			'branch' => $branch,
			'journalAccounting' => $journalAccounting,
			'generalLedgerSummary' => $generalLedgerSummary,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'number' => $number,
			'branchId' => $branchId,
			'accounts' => $accounts,
			'startAccount' => $startAccount,
			'endAccount' => $endAccount,
			'branchName' => $branchName
			
		));
	}
	
	protected function reportGrandTotal($dataProvider)
	{
		$grandTotal = 0.00;

		foreach ($dataProvider->data as $data)
			$grandTotal += $data->amountPaid;

		return $grandTotal;
	}
	
	public function actionAjaxHtmlAccount()									
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			
			$startAccount = (isset($_GET['StartAccount'])) ? $_GET['StartAccount'] : '';
			$endAccount = (isset($_GET['EndAccount'])) ? $_GET['EndAccount'] : '';
			
			$accounts = Account::model()->findAllByAttributes(
				array(
					'branch_id' => $_POST['BranchId'],
				),
				array(
					'order' => 'code ASC',
				)
			);
			
			$account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
			
			$this->renderPartial('_account', array(
				'account' => $account,
				'accounts' => $accounts,
				'startAccount' => $startAccount,
				'endAccount' => $endAccount,
			));
		}
	}
        
        protected function saveToExcel($generalLedgerSummary, $branch, $dataProvider, array $options = array())
	{
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
		spl_autoload_register(array('YiiBase', 'autoload'));

		$objPHPExcel = new PHPExcel();

		$documentProperties = $objPHPExcel->getProperties();
		$documentProperties->setCreator('Lanusa');
		$documentProperties->setTitle('Laporan Buku Besar');

		$worksheet = $objPHPExcel->setActiveSheetIndex(0);
		$worksheet->setTitle('Laporan Buku Besar');

		$worksheet->getColumnDimension('A')->setAutoSize(true);
		$worksheet->getColumnDimension('B')->setAutoSize(true);
		$worksheet->getColumnDimension('C')->setAutoSize(true);
		$worksheet->getColumnDimension('D')->setAutoSize(true);
		$worksheet->getColumnDimension('E')->setAutoSize(true);
		$worksheet->getColumnDimension('F')->setAutoSize(true);
             
		
		$worksheet->mergeCells('A1:F1');
		$worksheet->mergeCells('A2:F2');
		$worksheet->mergeCells('A3:F3');

		$worksheet->getStyle('A1:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A1:F6')->getFont()->setBold(true);

                
		$worksheet->setCellValue('A1',  CHtml::encode(CHtml::value($branch, 'name')));
		$worksheet->setCellValue('A2', 'Laporan Buku Besar');
		$worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
                
		$worksheet->setCellValue('A5', 'Akun');
		$worksheet->setCellValue('B5', 'Total Debit');
        $worksheet->setCellValue('C5', 'Total Kredit');
        $worksheet->mergeCells('D5:F5');
        $worksheet->setCellValue('D5', 'Saldo Akhir');
              
		$worksheet->setCellValue('A6', 'Transaksi #');
		$worksheet->setCellValue('B6', 'Memo');
		$worksheet->setCellValue('C6', 'Tanggal');
		$worksheet->setCellValue('D6', 'Debit');
		$worksheet->setCellValue('E6', 'Kredit');
        $worksheet->setCellValue('F6', 'Saldo');

        $worksheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

		$counter = 7;
              
		foreach ($dataProvider->data as $header)
		{
			$worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $worksheet->getStyle("B{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($header, 'code')).'-'.CHtml::encode(CHtml::value($header, 'name')));
            $worksheet->setCellValue("B{$counter}", $header->getEndDebitLedger($header->id, $options['startDate'],$options['endDate']));
            $worksheet->setCellValue("C{$counter}", $header->getEndCreditLedger($header->id, $options['startDate'],$options['endDate']));
            $worksheet->mergeCells("D{$counter}:F{$counter}");
            $worksheet->setCellValue("D{$counter}", $header->getEndBalanceLedger($header->id, $options['endDate']));
            $counter++;

            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("A{$counter}", 'SALDO AWAL');
            $worksheet->mergeCells("A{$counter}:C{$counter}");
            $worksheet->mergeCells("D{$counter}:F{$counter}");
            $worksheet->setCellValue("D{$counter}", $header->getBeginningBalanceLedger($header->id, $options['startDate']));
            $counter++;
                      
			foreach ($header->journalAccountings as $detail)
			{
                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'transaction_number')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))));
                $worksheet->setCellValue("D{$counter}", CHtml::encode($detail->debit));
                $worksheet->setCellValue("E{$counter}", CHtml::encode($detail->credit));
                $worksheet->setCellValue("F{$counter}", CHtml::encode($detail->currentSaldo));

                $counter++;
			}
		}
                
        for($col = 'A'; $col !== 'F'; $col++) {
			$objPHPExcel->getActiveSheet()
				->getColumnDimension($col)
				->setAutoSize(true);
		}

		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="Laporan Buku Besar.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		Yii::app()->end();
	}
}
