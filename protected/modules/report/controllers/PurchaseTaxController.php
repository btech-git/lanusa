<?php

class PurchaseTaxController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlSupplier') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $receiveHeader = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());
        $supplierId = isset($_GET['SupplierId']) ? $_GET['SupplierId'] : '';
        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $reference = isset($_GET['Reference']) ? $_GET['Reference'] : '';
		$taxNumber = isset($_GET['TaxNumber']) ? $_GET['TaxNumber'] : '';
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branch = Branch::model()->findByPk($branchId);
        $suppliers = Supplier::model()->findAllByAttributes(
			array(
				'branch_id' => $branchId
			), array(
				'order' => 'name ASC'
			)
        );

        $purchaseRecapSummary = new PurchaseTaxSummary($receiveHeader->search());
        $purchaseRecapSummary->setupLoading();
        $purchaseRecapSummary->setupPaging($pageSize, $currentPage);
        $purchaseRecapSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'reference' => $reference,
            'supplierId' => $supplierId,
			'taxNumber' => $taxNumber,
        );
        $purchaseRecapSummary->setupFilter($filters);

        if (isset($_POST['SaveToExcel'])) {
            $this->saveToExcel($purchaseRecapSummary, $startDate, $endDate);
        }

        $this->render('summary', array(
            'receiveHeader' => $receiveHeader,
            'purchaseRecapSummary' => $purchaseRecapSummary,
            'supplierId' => $supplierId,
            'branchId' => $branchId,
            'reference' => $reference,
			'taxNumber' => $taxNumber,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branch' => $branch,
            'suppliers' => $suppliers
        ));
    }

    protected function saveToExcel($purchaseRecapSummary, $startDate, $endDate) {
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
        $documentProperties->setTitle('Laporan Pajak Masukan');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Pajak Masukan');

        $worksheet->mergeCells('A1:H1');
        $worksheet->mergeCells('A2:H2');
        $worksheet->mergeCells('A3:H3');
        $worksheet->getStyle('A1:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:H3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', 'Lanusa');
        $worksheet->setCellValue('A2', 'Laporan Pajak Masukan');
        $worksheet->setCellValue('A3', $startDate . ' - ' . $endDate);

        $worksheet->mergeCells('A4:H4');
        $worksheet->mergeCells('A5:H5');

        $worksheet->getStyle("A6:H6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:H6")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A6:H6')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Penerimaan #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Invoice');
		$worksheet->setCellValue('D6', 'Faktur Pajak');
        $worksheet->setCellValue('E6', 'Supplier');
        $worksheet->setCellValue('F6', 'DPP');
        $worksheet->setCellValue('G6', 'PPN');
        $worksheet->setCellValue('H6', 'Total');

        $counter = 7;

        foreach ($purchaseRecapSummary->dataProvider->data as $header) {

            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber($header::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'reference'));
			$worksheet->setCellValue("D{$counter}", CHtml::value($header, 'supplier_tax_number'));
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, isset($header->purchaseHeader->supplier->company) ? 'purchaseHeader.supplier.company' : 'purchaseHeader.supplier.name')));
            $worksheet->getStyle("F{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("F{$counter}", CHtml::encode($header->totalPurchase));
            $worksheet->setCellValue("G{$counter}", CHtml::encode($header->purchaseTax));
            $worksheet->setCellValue("H{$counter}", CHtml::encode($header->grandTotalReceipt));

            $counter++;
        }

        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("E{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("E{$counter}", 'Total Pembelian');
        $worksheet->setCellValue("F{$counter}",  $this->reportGrandTotalPurchase($purchaseRecapSummary->dataProvider));
        $worksheet->setCellValue("G{$counter}",  $this->reportGrandTotalTax($purchaseRecapSummary->dataProvider));
        $worksheet->setCellValue("H{$counter}",  $this->reportGrandTotal($purchaseRecapSummary->dataProvider));

        $counter++;

        for ($col = 'A'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
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
            $grandTotal += $data->grandTotalReceipt;

        return $grandTotal;
    }

    protected function reportGrandTotalPurchase($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->totalPurchase;

        return $grandTotal;
    }

    protected function reportGrandTotalTax($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->purchaseTax;

        return $grandTotal;
    }

    public function actionAjaxHtmlSupplier() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $supplierId = '';
            $branchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : '';

            $suppliers = Supplier::model()->findAllByAttributes(
				array(
					'branch_id' => $branchId
				), array(
					'order' => 'name ASC'
				)
            );

            $this->renderPartial('_supplier', array(
                'suppliers' => $suppliers,
                'supplierId' => $supplierId
            ));
        }
    }

}
