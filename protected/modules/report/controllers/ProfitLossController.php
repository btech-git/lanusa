<?php

class ProfitLossController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'saveToExcel' || $filterChain->action->id === 'receiveAjaxData' || $filterChain->action->id === 'updateDataAjax') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
        $branchName = Branch::model()->findByPk($branchId);

        $accounts = ProfitLossSummary::getAccountList($branchId);

        $sql = SqlGenerator::profitLoss();
        $params = array(':end_date' => $endDate, ':branch_id' => $branchId);

        $row = Yii::app()->db->createCommand($sql)->queryRow(true, $params);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($row, $accounts, $startDate, $endDate, $branchId, $branchName);

        $this->render('summary', array(
            'row' => $row,
            'accounts' => $accounts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'branchName' => $branchName
        ));
    }

    public function actionReceiveAjaxData() {
        if (Yii::app()->request->isAjaxRequest) {
            $receiveId = (isset($_POST['ReceiveId'])) ? $_POST['ReceiveId'] : '';
            $receive = ReceiveHeader::model()->findByPk($receiveId);

            $object = array(
                'receive_header_number' => CHtml::value($receive, 'number'),
                'supplier_name' => CHtml::value($receive, 'supplier.name'),
            );

            echo CJSON::encode($object);
        }
    }

    public function actionUpdateDataAjax() {
        if (Yii::app()->request->isAjaxRequest) {
            $receiveId = (isset($_POST['ReceiveId'])) ? $_POST['ReceiveId'] : '';

            $sql = SqlGenerator::profitLoss(empty($receiveId));

            $rows = Yii::app()->db->createCommand($sql)->queryRow(true, array(':receive_id' => $receiveId));

            $this->renderPartial('_report', array(
                'rows' => $rows,
            ));
        }
    }

    protected function saveToExcel($row, $accounts, $startDate, $endDate, $branchId, $branchName) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        $startDateString = Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate);
        $endDateString = Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate);

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Laba Rugi');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Laba Rugi');

        $worksheet->mergeCells('A1:C1');
        $worksheet->mergeCells('A2:C2');
        $worksheet->mergeCells('A3:C3');
        $worksheet->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:C3')->getFont()->setBold(true);

        $branch = Branch::model()->findByPk($branchId);
        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branchName, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Laba / Rugi');
        $worksheet->setCellValue('A3', $startDateString . ' - ' . $endDateString);


        $counter = 6;


        $worksheet->setCellValue("B{$counter}", CHtml::value($accounts['sale'], 'name'));
        $counter++;

        foreach ($accounts['sale']->accounts as $account):
            if ($account->branch_id == $branchId):
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("C{$counter}", CHtml::encode($account->getBalanceTotal($startDate, $endDate, $branchId)));
                $counter++;
            endif;
        endforeach;

        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['sale_amount']);
        $counter++;
        $counter++;
        $worksheet->setCellValue("B{$counter}", 'Stock Awal');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['beginning_stock_amount']);
        $counter++;
        $counter++;
        $worksheet->setCellValue("B{$counter}", CHtml::value($accounts['purchase'], 'name'));
        $counter++;

        foreach ($accounts['purchase']->accounts as $account):
            if ($account->branch_id == $branchId):
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("C{$counter}", CHtml::encode($account->getBalanceTotal($startDate, $endDate, $branchId)));
                $counter++;
            endif;
        endforeach;

        $worksheet->setCellValue("B{$counter}", 'Barang Siap Jual');
        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['purchase_amount']);
        $counter++;

        $worksheet->setCellValue("B{$counter}", 'Stock Akhir');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['ending_stock_amount']);
        $counter++;
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'HPP');
        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['cogs']);
        $counter++;

        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->setCellValue("B{$counter}", 'Laba Kotor');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['gross']);
        $counter++;
        $counter++;

        $worksheet->setCellValue("B{$counter}", CHtml::value($accounts['expense'], 'name'));
        $counter++;

        foreach ($accounts['expense']->accountCategories as $accountCategory):
            foreach ($accountCategory->accounts as $account):
                if ($account->branch_id == $branchId):
                    $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                    $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                    $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $worksheet->setCellValue("C{$counter}", CHtml::encode($account->getBalanceTotal($startDate, $endDate, $branchId)));
                    $counter++;
                endif;
            endforeach;
        endforeach;

        $worksheet->setCellValue("A{$counter}", 'Total Biaya');
        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['expense_amount']);
        $counter++;
        $counter++;

        $worksheet->setCellValue("B{$counter}", CHtml::value($accounts['otherIncome'], 'name'));
        $counter++;


        foreach ($accounts['otherIncome']->accounts as $account):
            if ($account->branch_id == $branchId):
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("C{$counter}", CHtml::encode($account->getBalanceTotal($startDate, $endDate, $branchId)));
                $counter++;
            endif;
        endforeach;

        $worksheet->setCellValue("A{$counter}", 'Total Pendapatan Lain - lain');
        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['other_income_amount']);
        $counter++;
        $counter++;

        $worksheet->setCellValue("B{$counter}", CHtml::value($accounts['otherExpense'], 'name'));
        $counter++;


        foreach ($accounts['otherExpense']->accounts as $account):
            if ($account->branch_id == $branchId):
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("C{$counter}", CHtml::encode($account->getBalanceTotal($startDate, $endDate, $branchId)));
                $counter++;
            endif;
        endforeach;

        $worksheet->setCellValue("A{$counter}", 'Total Biaya Lain - lain');
        $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['other_expense_amount']);
        $counter++;
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Laba / Rugi');
        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", $row['profit_loss']);
        $counter++;

        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Laba Rugi.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    protected function saveToExcelBackUp($dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('');
        $documentProperties->setTitle('Profit Loss');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Profit Loss');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);

        $worksheet->mergeCells('A1:B1');
        $worksheet->mergeCells('A2:B2');

        $worksheet->getStyle('A1:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A7')->getFont()->setBold(true);
        $worksheet->getStyle('A9')->getFont()->setBold(true);
        $worksheet->getStyle('A6:B6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A8:B8')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->setCellValue('A1', 'Laporan Profit / Loss');

        $worksheet->setCellValue('A4', 'SALES');
        $worksheet->setCellValue('A5', 'PURCHASE');
        $worksheet->setCellValue('A6', 'GROSS');
        $worksheet->setCellValue('A7', 'Expense');
        $worksheet->setCellValue('A8', 'PROFIT/LOSS');

        $counter = 8;

        $worksheet->getStyle("B4{$counter}")->getNumberFormat()->setFormatCode('#,##0.00');
        $worksheet->getStyle("B5{$counter}")->getNumberFormat()->setFormatCode('#,##0.00');
        $worksheet->getStyle("B6{$counter}")->getNumberFormat()->setFormatCode('#,##0.00');
        $worksheet->getStyle("B7{$counter}")->getNumberFormat()->setFormatCode('#,##0.00');
        $worksheet->getStyle("B8{$counter}")->getNumberFormat()->setFormatCode('#,##0.00');

        $worksheet->setCellValue("B4", $dataProvider['sales_amount']);
        $worksheet->setCellValue("B5", $dataProvider['purchase_amount']);
        $worksheet->setCellValue("B6", $dataProvider['gross']);
        $worksheet->setCellValue("B7", $dataProvider['expense_amount']);
        $worksheet->setCellValue("B8", $dataProvider['profit_loss']);

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="profitloss.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
