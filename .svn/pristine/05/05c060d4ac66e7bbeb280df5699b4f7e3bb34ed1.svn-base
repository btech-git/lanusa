<?php

class SaleDownpaymentController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('saleDownpaymentReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $saleDownpayment = Search::bind(new SaleDownpayment('search'), isset($_GET['SaleDownpayment']) ? $_GET['SaleDownpayment'] : array());
        //$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $saleDownpaymentSummary = new SaleDownpaymentSummary($saleDownpayment->search());
        $saleDownpaymentSummary->setupLoading();
        $saleDownpaymentSummary->setupPaging($pageSize, $currentPage);
        $saleDownpaymentSummary->setupSorting();
        $saleDownpaymentSummary->setupFilter($startDate, $endDate);
        $saleDownpaymentSummary->setupBranch($branch);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleDownpaymentSummary, $startDate, $endDate);

        $this->render('summary', array(
            'saleDownpayment' => $saleDownpayment,
            'saleDownpaymentSummary' => $saleDownpaymentSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'listData' => $listData
        ));
    }

    protected function saveToExcel($saleDownpaymentSummary, $startDate, $endDate) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        $startDate = Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate);
        $endDate = Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate);

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Uang Muka');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Uang Muka');

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');
        $worksheet->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', 'Lanusa');
        $worksheet->setCellValue('A2', 'Laporan Uang Muka Customer');
        $worksheet->setCellValue('A3', $startDate . ' - ' . $endDate);

        $worksheet->mergeCells('A4:G4');
        $worksheet->mergeCells('A5:G5');

        $worksheet->getStyle("A6:G6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:G6")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A6:G6')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Uang Muka #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Customer');
        $worksheet->setCellValue('D6', 'Quantity');
        $worksheet->setCellValue('E6', 'Jumlah');
        $worksheet->setCellValue('F6', 'Pajak');
        $worksheet->setCellValue('G6', 'Catatan');

        $counter = 7;

        foreach ($saleDownpaymentSummary->dataProvider->data as $header) {

            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber($header::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'customer.name' : 'customer.company')));
            $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->quantity));
            $worksheet->setCellValue("E{$counter}", CHtml::encode($header->amount));
            $worksheet->setCellValue("F{$counter}", CHtml::encode($header->tax));
            $worksheet->setCellValue("G{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));
            $counter++;
        }


        $counter++;

        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Uang Muka Customer.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
