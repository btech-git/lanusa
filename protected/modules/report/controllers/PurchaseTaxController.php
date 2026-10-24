<?php

class PurchaseTaxController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport'))) {
                $this->redirect(array('/site/login'));
            }
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
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branch = Branch::model()->findByPk($branchId);
        $suppliers = Supplier::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'company ASC'
        ));

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

        if (isset($_GET['SaveToExcel'])) {
            $this->saveToExcel($purchaseRecapSummary, $startDate, $endDate, $branch);
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

    protected function saveToExcel($purchaseRecapSummary, $startDate, $endDate, $branch) {
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
        
        $worksheet->getStyle('A1:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:H5')->getFont()->setBold(true);
        
        $worksheet->setCellValue('A1', empty($branch) ? 'PT Logam Nusantara' : CHtml::value($branch, 'name'));
        $worksheet->setCellValue('A2', 'Laporan Pajak Masukan');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate));

        $worksheet->getStyle("A5:H5")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A5:H5")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'PO #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Invoice');
        $worksheet->setCellValue('D5', 'Faktur Pajak');
        $worksheet->setCellValue('E5', 'Supplier');
        $worksheet->setCellValue('F5', 'DPP');
        $worksheet->setCellValue('G5', 'PPN');
        $worksheet->setCellValue('H5', 'Total');

        $counter = 6;

        $subTotalSum = '0.00';
        $taxAmountSum = '0.00'; 
        $grandTotalSum = '0.00';
        
        foreach ($purchaseRecapSummary->dataProvider->data as $header) {
            $subTotal = CHtml::value($header, 'totalPurchase');
            $taxAmount = CHtml::value($header, 'purchaseTax');
            $grandTotal = CHtml::value($header, 'grandTotalReceipt');
            
            $worksheet->setCellValue("A{$counter}", $header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", $header->purchaseHeader->date);
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'reference'));
            $worksheet->setCellValue("D{$counter}", CHtml::value($header, 'supplier_tax_number'));
            $worksheet->setCellValue("E{$counter}", CHtml::value($header, 'purchaseHeader.supplier.company'));
            $worksheet->setCellValue("F{$counter}", $subTotal);
            $worksheet->setCellValue("G{$counter}", $taxAmount);
            $worksheet->setCellValue("H{$counter}", $grandTotal);

            $subTotalSum += $subTotal; 
            $taxAmountSum += $taxAmount;
            $grandTotalSum += $grandTotal;

            $counter++;
        }

        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);

        $worksheet->setCellValue("E{$counter}", 'Total');
        $worksheet->setCellValue("F{$counter}",  $subTotalSum);
        $worksheet->setCellValue("G{$counter}",  $taxAmountSum);
        $worksheet->setCellValue("H{$counter}",  $grandTotalSum);

        $counter++;

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pajak_masukan.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = '0.00';

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->grandTotalReceipt;
        }

        return $grandTotal;
    }

    protected function reportGrandTotalPurchase($dataProvider) {
        $grandTotal = '0.00';

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->totalPurchase;
        }

        return $grandTotal;
    }

    protected function reportGrandTotalTax($dataProvider) {
        $grandTotal = '0.00';

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->purchaseTax;
        }

        return $grandTotal;
    }

    public function actionAjaxHtmlSupplier() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $supplierId = '';
            $branchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : '';

            $suppliers = Supplier::model()->findAllByAttributes(array(
                'branch_id' => $branchId
            ), array(
                'order' => 'name ASC'
            ));

            $this->renderPartial('_supplier', array(
                'suppliers' => $suppliers,
                'supplierId' => $supplierId
            ));
        }
    }
}