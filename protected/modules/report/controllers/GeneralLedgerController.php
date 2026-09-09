<?php

class GeneralLedgerController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'ajaxHtmlAccount') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport'))) {
                $this->redirect(array('/site/login'));
            }
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
        $accountIds = (isset($_GET['AccountIds'])) ? $_GET['AccountIds'] : '';

        $accountIdList = $accountIds === '' ? array() : explode(',', $accountIds);

        $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
        $accountDataProvider = $account->searchByReport();
        $accountDataProvider->pagination->pageVar = 'page_dialog';

        $generalLedgerSummary = new GeneralLedgerSummary($account->search());
        $generalLedgerSummary->setupLoading();
        $generalLedgerSummary->setupPaging($pageSize, $currentPage);
        $generalLedgerSummary->setupSorting();
        $generalLedgerSummary->setupFilter($accountIdList, $startDate, $endDate);
        
        $coaIds = array_map(function($coa) { return $coa->id; }, $generalLedgerSummary->dataProvider->data);
        
        $ledgerBeginningBalances = JournalAccounting::getLedgerBeginningBalances($coaIds, $startDate);
        $ledgerBeginningBalanceData = array();
        foreach ($ledgerBeginningBalances as $ledgerBeginningBalance) {
            $ledgerBeginningBalanceData[$ledgerBeginningBalance['account_id']] = $ledgerBeginningBalance['beginning_balance'];
        }
        
        $generalLedgerReport = JournalAccounting::getGeneralLedgerReport($coaIds, $startDate, $endDate);
        $generalLedgerReportData = array();
        foreach ($generalLedgerReport as $generalLedgerReportItem) {
            $generalLedgerReportData[$generalLedgerReportItem['account_id']][] = $generalLedgerReportItem;
        }

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($generalLedgerReportData, $ledgerBeginningBalanceData, $generalLedgerSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));
        }

        $this->render('summary', array(
            'account' => $account,
            'generalLedgerSummary' => $generalLedgerSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'accountIds' => $accountIds,
            'accountDataProvider' => $accountDataProvider,
            'currentSort' => $currentSort,
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'ledgerBeginningBalanceData' => $ledgerBeginningBalanceData,
            'generalLedgerReportData' => $generalLedgerReportData,
        ));
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;
 
        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->amountPaid;
        }

        return $grandTotal;
    }

    public function actionAjaxHtmlAccount() {
        if (Yii::app()->request->isAjaxRequest) {
            $accountIds = (isset($_GET['AccountIds'])) ? $_GET['AccountIds'] : '';
            
            $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
            $accountDataProvider = $account->searchByReport();
            $accountDataProvider->criteria->compare('t.is_inactive', 0);
            $accountDataProvider->pagination->pageVar = 'page_dialog';
            
            $this->renderPartial('_account', array(
                'account' => $account,
                'accountIds' => $accountIds,
                'accountDataProvider' => $accountDataProvider,
            ));
        }
    }

    protected function saveToExcel($generalLedgerReportData, $ledgerBeginningBalanceData, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Buku Besar');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Buku Besar');

        $worksheet->mergeCells('A1:F1');
        $worksheet->mergeCells('A2:F2');
        $worksheet->mergeCells('A3:F3');

        $worksheet->getStyle('A1:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F6')->getFont()->setBold(true);

//        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Buku Besar');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('B5', 'Code');
        $worksheet->setCellValue('C5', 'Akun');
        $worksheet->setCellValue('D5', 'Saldo Awal');

        $worksheet->setCellValue('A6', 'Transaksi #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Memo');
        $worksheet->setCellValue('D6', 'Debit');
        $worksheet->setCellValue('E6', 'Kredit');
        $worksheet->setCellValue('F6', 'Saldo');

        $worksheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        $accountNumber = $dataProvider->pagination->getCurrentPage(false) * $dataProvider->pagination->pageSize + 1;

        foreach ($dataProvider->data as $i => $header) {
            $beginningBalance = isset($ledgerBeginningBalanceData[$header->id]) ? $ledgerBeginningBalanceData[$header->id] : '0.00';

            $worksheet->setCellValue("A{$counter}", $accountNumber++);
            $worksheet->setCellValue("B{$counter}", CHtml::value($header, 'code'));
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'name'));
            $worksheet->setCellValue("D{$counter}", $beginningBalance);
            $counter++;

            $totalDebit = '0.00';
            $totalCredit = '0.00';
            if (isset($generalLedgerReportData[$header->id])) {
                $generalLedgerData = $generalLedgerReportData[$header->id];
                $currentBalance = $beginningBalance; 
                
                foreach ($generalLedgerData as $generalLedgerRow) {
                    $debitAmount = $generalLedgerRow['debit'];
                    $creditAmount = $generalLedgerRow['credit'];
                    $currentBalance += $debitAmount - $creditAmount;

                    $worksheet->setCellValue("A{$counter}", $generalLedgerRow['transaction_number']);
                    $worksheet->setCellValue("B{$counter}", $generalLedgerRow['date']);
                    $worksheet->setCellValue("C{$counter}", $generalLedgerRow['memo']);
                    $worksheet->setCellValue("D{$counter}", $debitAmount);
                    $worksheet->setCellValue("E{$counter}", $creditAmount);
                    $worksheet->setCellValue("F{$counter}", $currentBalance);

                    $totalDebit += $debitAmount;
                    $totalCredit += $creditAmount;
                    $counter++;
                }
            }
            $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->mergeCells("A{$counter}:C{$counter}");
            
            $worksheet->setCellValue("A{$counter}", 'TOTAL');
            $worksheet->setCellValue("D{$counter}", $totalDebit);
            $worksheet->setCellValue("E{$counter}", $totalCredit);
        }

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="buku_besar.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
