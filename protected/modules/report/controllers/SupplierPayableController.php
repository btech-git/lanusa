<?php

class SupplierPayableController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlAccount') {
            if (!(Yii::app()->user->checkAccess('allFinanceReport')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        
        $accounts = Account::model()->findAll(array(
            'condition' => 't.account_category_id = 17 AND t.is_inactive = 0 AND t.branch_id = :branch_id',
            'params' => array(':branch_id' => $branchId),
            'order' => 't.code'
        ));

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($accounts, $branchId, $endDate);

        $this->render('summary', array(
            'accounts' => $accounts,
            'branchId' => $branchId,
            'endDate' => $endDate
        ));
    }

    public function actionAjaxHtmlAccount() {
        if (Yii::app()->request->isAjaxRequest) {
            $branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';
            $startAccount = (isset($_POST['StartAccount'])) ? $_POST['StartAccount'] : '';
            $endAccount = (isset($_POST['EndAccount'])) ? $_POST['EndAccount'] : '';

            $accounts = Account::model()->findAll(array(
                'condition' => 'account_category_id = 17 AND branch_id = :branchId',
                'params' => array(
                    ':branchId' => $branchId
                ),
                'order' => 'name ASC'
                    ));

            $this->renderPartial('_account', array(
                'accounts' => $accounts,
                'startAccount' => $startAccount,
                'endAccount' => $endAccount
            ));
        }
    }

    protected function saveToExcel($accounts, $branchId, $endDate) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Hutang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Hutang');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);

        $worksheet->mergeCells('A1:C1');
        $worksheet->mergeCells('A2:C2');
        $worksheet->mergeCells('A3:C3');

        $worksheet->getStyle('A1:C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:C5')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', '');
        $worksheet->setCellValue('A2', 'Laporan Hutang');
        $worksheet->setCellValue('A3', '');

        $worksheet->getStyle('A5:C5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Kode');
        $worksheet->setCellValue('B5', 'Nama Akun');
        $worksheet->setCellValue('C5', 'Total Hutang');

        $worksheet->getStyle('A5:C5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;
        $total = 0.00;
        foreach ($accounts as $account) {
            $totalPayable = $account->getSupplierPayableBalance($branchId, $endDate);
            if ($totalPayable > 100):
                $worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($account, 'code')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($account, 'name')));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $totalPayable)));
                $counter++;

                $total += $totalPayable;
            endif;
        }

        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("B{$counter}", 'TOTAL');
        $worksheet->setCellValue("C{$counter}", CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($total))));


        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Hutang.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
