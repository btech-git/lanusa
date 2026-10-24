<?php

class SaleTaxController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('allAccountingReport') )) {
                $this->redirect(array('/site/login'));
            }
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
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branch = Branch::model()->findByPk($branchId);
        $customers = Customer::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'company ASC'
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

        if (isset($_GET['SaveToExcel'])) {
            $this->saveToExcel($saleRecapSummary, $startDate, $endDate, $branch);
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

    protected function saveToExcel($saleRecapSummary, $startDate, $endDate, $branch) {
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
        
        $worksheet->getStyle('A1:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G5')->getFont()->setBold(true);
        
        $worksheet->setCellValue('A1', empty($branch) ? 'PT Logam Nusantara' : CHtml::value($branch, 'name'));
        $worksheet->setCellValue('A2', 'Laporan Pajak Keluaran');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate));

        $worksheet->getStyle("A5:G5")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A5:G5")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Invoice #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Reference');
        $worksheet->setCellValue('D5', 'Customer');
        $worksheet->setCellValue('E5', 'DPP');
        $worksheet->setCellValue('F5', 'PPN');
        $worksheet->setCellValue('G5', 'Total');

        $counter = 6;

        $subTotalSum = '0.00';
        $taxAmountSum = '0.00'; 
        $grandTotalSum = '0.00';
        
        foreach ($saleRecapSummary->dataProvider->data as $header) {
            $subTotal = CHtml::value($header, 'totalBeforeTax');
            $taxAmount = CHtml::value($header, 'calculatedTax');
            $grandTotal = CHtml::value($header, 'grandTotal');
            
            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber($header::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'reference'));
            $worksheet->setCellValue("D{$counter}", CHtml::value($header, 'deliveryHeader.saleHeader.customer.company'));
            $worksheet->setCellValue("E{$counter}", $subTotal);
            $worksheet->setCellValue("F{$counter}", $taxAmount);
            $worksheet->setCellValue("G{$counter}", $grandTotal);
            
            $subTotalSum += $subTotal; 
            $taxAmountSum += $taxAmount;
            $grandTotalSum += $grandTotal;

            $counter++;
        }

        $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);

        $worksheet->setCellValue("D{$counter}", 'Total');
        $worksheet->setCellValue("E{$counter}",  $subTotalSum);
        $worksheet->setCellValue("F{$counter}",  $taxAmountSum);
        $worksheet->setCellValue("G{$counter}",  $grandTotalSum);

        $counter++;

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pajak_keluaran.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->grandTotal;
        }

        return $grandTotal;
    }

    protected function reportGrandTotalSale($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->totalBeforeTax;
        }

        return $grandTotal;
    }

    protected function reportGrandTotalTax($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->calculatedTax;
        }

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