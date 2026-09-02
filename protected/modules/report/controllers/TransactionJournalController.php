<?php

class TransactionJournalController extends Controller {

    public function filters() {
        return array(
//            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'balanceErrorSummary') {
            if (!(Yii::app()->user->checkAccess('transactionJournalReport'))) {
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
        $transactionType = (isset($_GET['TransactionType'])) ? $_GET['TransactionType'] : '';
        $branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
        $coaId = (isset($_GET['CoaId'])) ? $_GET['CoaId'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $pageSize = 500;
        
        $transactionJournalReport = JournalAccounting::getTransactionJournalReport($startDate, $endDate, $transactionType, $branchId, $coaId, $currentPage, $pageSize);
        $transactionJournalCount = JournalAccounting::getTransactionJournalCount($startDate, $endDate, $transactionType, $branchId, $coaId);
        
        $transactionJournalReportTransactionCodes = array_map(function($transactionJournalReportItem) { return $transactionJournalReportItem['transaction_number']; }, $transactionJournalReport);
        $transactionJournalItems = JournalAccounting::model()->findAllByAttributes(array('transaction_number' => $transactionJournalReportTransactionCodes));
        $transactionJournalReportData = array();
        foreach ($transactionJournalItems as $transactionJournalItem) {
            if (!isset($transactionJournalReportData[$transactionJournalItem->transaction_number])) {
                $transactionJournalReportData[$transactionJournalItem->transaction_number] = array();
            }
            $transactionJournalReportData[$transactionJournalItem->transaction_number][] = $transactionJournalItem;
        }
        
        $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
        $accountDataProvider = $account->search();
        $accountDataProvider->criteria->compare('t.is_inactive', 0);

        if (isset($_GET['ResetFilter'])) {
            $this->redirect(array('summary'));
        }
        
        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($transactionJournalReport, $transactionJournalReportData, array(
                'transactionJournalCount' => $transactionJournalCount,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'transactionType' => $transactionType,
                'branchId' => $branchId,
                'coaId' => $coaId,
                'account' => $account,
                'accountDataProvider' => $accountDataProvider,
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
            ));
        }
        
        $this->render('summary', array(
            'transactionJournalReport' => $transactionJournalReport,
            'transactionJournalReportData' => $transactionJournalReportData,
            'transactionJournalCount' => $transactionJournalCount,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'transactionType' => $transactionType,
            'branchId' => $branchId,
            'coaId' => $coaId,
            'account' => $account,
            'accountDataProvider' => $accountDataProvider,
            'currentPage' => $currentPage,
            'pageSize' => $pageSize,
        ));
    }

    public function actionAjaxJsonCoa() {
        if (Yii::app()->request->isAjaxRequest) {
            $coaId = (isset($_POST['CoaId'])) ? $_POST['CoaId'] : '';
            $coa = Account::model()->findByPk($coaId);

            $object = array(
                'coa_name' => CHtml::value($coa, 'name'),
                'coa_code' => CHtml::value($coa, 'code'),
            );
            
            echo CJSON::encode($object);
        }
    }

    protected function saveToExcel($transactionJournalReport, $transactionJournalReportData, array $options = array()) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        
        $startDate = (empty($options['startDate'])) ? date('Y-m-d') : $options['startDate'];
        $endDate = (empty($options['endDate'])) ? date('Y-m-d') : $options['endDate'];

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Jurnal Umum');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Jurnal Umum');

        $worksheet->mergeCells('A1:H1');
        $worksheet->mergeCells('A2:H2');
        $worksheet->mergeCells('A3:H3');

        $worksheet->getStyle('A1:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:H6')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', 'Lanusa');
        $worksheet->setCellValue('A2', 'Jurnal Umum');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate)) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate)));

        $worksheet->setCellValue('A5', 'No');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Kode Transaksi');
        $worksheet->setCellValue('D5', 'Keterangan');
        $worksheet->setCellValue('E5', 'Kode COA');
        $worksheet->setCellValue('F5', 'Nama COA');
        $worksheet->setCellValue('G5', 'Debit');
        $worksheet->setCellValue('H5', 'Kredit');

        $worksheet->getStyle('A5:G5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7; 
        
        foreach ($transactionJournalReport as $i => $header) {
            $totalDebit = 0; 
            $totalCredit = 0; 
            foreach ($transactionJournalReportData[$header['transaction_number']] as $transactionJournalItemData) {
                $debitAmount = $transactionJournalItemData->debit;
                $creditAmount = $transactionJournalItemData->credit;
                
                $worksheet->setCellValue("A{$counter}", CHtml::encode($i + 1));
                $worksheet->setCellValue("B{$counter}", CHtml::encode($header['transaction_date']));
                $worksheet->setCellValue("C{$counter}", CHtml::encode($header['transaction_number']));
                $worksheet->setCellValue("D{$counter}", CHtml::encode($header['transaction_subject']));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($transactionJournalItemData, 'account.code')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($transactionJournalItemData, 'account.name')));
                $worksheet->setCellValue("G{$counter}", CHtml::encode($debitAmount));
                $worksheet->setCellValue("H{$counter}", CHtml::encode($creditAmount));
                
                $totalDebit += $debitAmount;
                $totalCredit += $creditAmount;
                
                $counter++;
            }
            $worksheet->mergeCells("A{$counter}:F{$counter}");
            $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("A{$counter}", 'TOTAL');
            $worksheet->setCellValue("G{$counter}", $totalDebit);
            $worksheet->setCellValue("H{$counter}", $totalCredit);
            $counter++;$counter++;

        }

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        ob_end_clean();
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="jurnal_umum.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}