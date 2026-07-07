<?php

class PurchaseRecapController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlSupplier') {
            if (!(Yii::app()->user->checkAccess('purchaseReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $purchaseHeader = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());
        $supplierId = isset($_GET['SupplierId']) ? $_GET['SupplierId'] : '';
        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
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

        $purchaseRecapSummary = new PurchaseRecapSummary($purchaseHeader->search());
        $purchaseRecapSummary->setupLoading();
        $purchaseRecapSummary->setupPaging($pageSize, $currentPage);
        $purchaseRecapSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $purchaseRecapSummary->setupFilter($filters);

        if (isset($_POST['SaveToExcel']))
            $this->saveToExcel($purchaseRecapSummary, $startDate, $endDate);

        $this->render('summary', array(
            'purchaseHeader' => $purchaseHeader,
            'purchaseRecapSummary' => $purchaseRecapSummary,
            'supplierId' => $supplierId,
            'branchId' => $branchId,
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
        $documentProperties->setTitle('Laporan Order Pembelian');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Order Pembelian');

        $worksheet->mergeCells('A1:F1');
        $worksheet->mergeCells('A2:F2');
        $worksheet->mergeCells('A3:F3');
        $worksheet->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', 'Lanusa');
        $worksheet->setCellValue('A2', 'Laporan Order Pembelian');
        $worksheet->setCellValue('A3', $startDate . ' - ' . $endDate);

        $worksheet->mergeCells('A4:F4');
        $worksheet->mergeCells('A5:F5');

        $worksheet->getStyle("A6:F6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:F6")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A6:F6')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Pembelian #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Supplier');
        $worksheet->setCellValue('D6', 'Sub Total');
        $worksheet->setCellValue('E6', 'PPN');
        $worksheet->setCellValue('F6', 'Total');

        $worksheet->setCellValue('A7', 'Penerimaan #');
        $worksheet->setCellValue('B7', 'Tanggal');
        $worksheet->setCellValue('C7', 'Faktur Pajak #');
        $worksheet->setCellValue('D7', 'SJ #');
		$worksheet->setCellValue('E7', 'Total');

        $counter = 8;

        foreach ($purchaseRecapSummary->dataProvider->data as $header) {

            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber($header::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, isset($header->supplier->company) ? 'supplier.company' : 'supplier.name')));
            $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->totalBeforeTax));
            $worksheet->setCellValue("E{$counter}", CHtml::encode($header->calculatedTax));
            $worksheet->setCellValue("F{$counter}", CHtml::encode($header->grandTotal));
            $counter++;
			
            foreach ($header->receiveHeaders as $detail) {
                $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->getCodeNumber(ReceiveHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'supplier_tax_number')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'reference')));
				$worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'totalPurchase')));

                $counter++;
            }

        }

        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("E{$counter}", 'Total Pembelian');
        $worksheet->setCellValue("F{$counter}", $this->reportGrandTotal($purchaseRecapSummary->dataProvider));

        $counter++;

        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pembelian.xlsx"');
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

    public function actionAjaxHtmlSupplier() {         //find supplier based on selected branch
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
