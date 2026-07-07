<?php

class SaleTaxController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());
        $customerId = isset($_GET['CustomerId']) ? $_GET['CustomerId'] : '';
        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $reference = isset($_GET['Reference']) ? $_GET['Reference'] : '';
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branch = Branch::model()->findByPk($branchId);
        $customers = Customer::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'name ASC'
        ));

        $saleRecapSummary = new SaleTaxSummary($saleInvoice->search());
        $saleRecapSummary->setupLoading();
        $saleRecapSummary->setupPaging($pageSize, $currentPage);
        $saleRecapSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'reference' => $reference,
            'customerId' => $customerId
        );
        $saleRecapSummary->setupFilter($filters);

        if (isset($_POST['SaveToExcel'])) {
            $this->saveToExcel($saleRecapSummary, $startDate, $endDate);
        }

        $this->render('summary', array(
            'saleInvoice' => $saleInvoice,
            'saleRecapSummary' => $saleRecapSummary,
            'customerId' => $customerId,
            'branchId' => $branchId,
            'reference' => $reference,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branch' => $branch,
            'customers' => $customers
        ));
    }

    protected function saveToExcel($saleRecapSummary, $startDate, $endDate) {
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
        $documentProperties->setTitle('Laporan Pajak Keluaran');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Pajak Keluaran');

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');
        $worksheet->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', 'Lanusa');
        $worksheet->setCellValue('A2', 'Laporan Pajak Keluaran');
        $worksheet->setCellValue('A3', $startDate . ' - ' . $endDate);

        $worksheet->mergeCells('A4:G4');
        $worksheet->mergeCells('A5:G5');

        $worksheet->getStyle("A6:G6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:G6")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A6:G6')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Invoice #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Reference');
        $worksheet->setCellValue('D6', 'Customer');
        $worksheet->setCellValue('E6', 'DPP');
        $worksheet->setCellValue('F6', 'PPN');
        $worksheet->setCellValue('G6', 'Total');

        $counter = 7;

        foreach ($saleRecapSummary->dataProvider->data as $header) {


            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber($header::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'reference'));
            $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($header, isset($header->deliveryHeader->saleHeader->customer->company) ? 'deliveryHeader.saleHeader.customer.company' : 'deliveryHeader.saleHeader.customer.name')));
            $worksheet->getStyle("E{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("E{$counter}", CHtml::encode($header->totalBeforeTax));
            $worksheet->setCellValue("F{$counter}", CHtml::encode($header->calculatedTax));
            $worksheet->setCellValue("G{$counter}", CHtml::encode($header->grandTotal));

            $counter++;
        }

        $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("D{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("D{$counter}", 'Total Penjualan');
        $worksheet->setCellValue("E{$counter}",  $this->reportGrandTotalSale($saleRecapSummary->dataProvider));
        $worksheet->setCellValue("F{$counter}",  $this->reportGrandTotalTax($saleRecapSummary->dataProvider));
        $worksheet->setCellValue("G{$counter}",  $this->reportGrandTotal($saleRecapSummary->dataProvider));

        $counter++;

        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Invoice.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

    protected function reportGrandTotalSale($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->totalBeforeTax;

        return $grandTotal;
    }

    protected function reportGrandTotalTax($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->calculatedTax;

        return $grandTotal;
    }

    public function actionAjaxHtmlCustomer() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $customerId = '';
            $branchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : '';

            $customers = Customer::model()->findAllByAttributes(array(
                'branch_id' => $branchId
            ), array(
                'order' => 'name ASC'
            ));

            $this->renderPartial('_customer', array(
                'customers' => $customers,
                'customerId' => $customerId
            ));
        }
    }

}
