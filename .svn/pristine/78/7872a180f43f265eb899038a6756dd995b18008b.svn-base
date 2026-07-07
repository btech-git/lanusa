<?php

class SaleReceiptController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('saleReceiptReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $saleReceipt = Search::bind(new SaleReceiptHeader('search'), isset($_GET['SaleReceiptHeader']) ? $_GET['SaleReceiptHeader'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);
        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';
        $customers = Customer::model()->findAllByAttributes(
            array(
                'branch_id' => $branchId
            ), array(
                'order' => 'name ASC'
            )
        );

        $saleReceiptSummary = new SaleReceiptSummary($saleReceipt->search());
        $saleReceiptSummary->setupLoading();
        $saleReceiptSummary->setupPaging($pageSize, $currentPage);
        $saleReceiptSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'customerId' => $customerId
        );
        $saleReceiptSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleReceiptSummary, $branch, $saleReceiptSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'saleReceipt' => $saleReceipt,
            'saleReceiptSummary' => $saleReceiptSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'branch' => $branch,
            'customerId' => $customerId,
            'customers' => $customers
        ));
    }

    public function actionAjaxHtmlCustomer() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $customerId = '';
            $branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';

            $customers = Customer::model()->findAllByAttributes(
                array(
                    'branch_id' => $branchId
                ), array(
                    'order' => 'name ASC'
                )
            );

            $this->renderPartial('_customer', array(
                'customers' => $customers,
                'customerId' => $customerId
            ));
        }
    }

    protected function saveToExcel($saleReceiptSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Tanda Terima Penjualan Detail');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Tanda Terima Penjualan Detail');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);
        $worksheet->getColumnDimension('G')->setAutoSize(true);

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');

        $worksheet->getStyle('A1:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G5')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', '');
        $worksheet->setCellValue('A2', 'Laporan Tanda Terima Penjualan Detail');
        $worksheet->setCellValue('A3', '');

        $worksheet->getStyle('A5:G5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanda Terima');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Invoice');
        $worksheet->setCellValue('D5', 'Tanggal Invoice');
        $worksheet->setCellValue('E5', 'Customer');
        $worksheet->setCellValue('F5', 'Total Invoice');
        $worksheet->setCellValue('G5', 'Catatan');

        $worksheet->getStyle('A5:G5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;
        $grandTotalInvoice = 0.00;
//        $totalPayment = 0.00;
//        $totalRemaining = 0.00;
        foreach ($dataProvider->data as $header) {
            foreach ($header->saleReceiptDetails as $detail) {
            $totalInvoice = CHtml::value($detail, 'saleInvoice.grandTotal');
//            $payment = CHtml::value($header, 'total_payment');
//            $remaining = CHtml::value($header, 'remaining');
            
                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode($detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->saleInvoice->date))));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'customer.company')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode($totalInvoice));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($header, 'note')));
                $counter++;

            $grandTotalInvoice += $totalInvoice;
//            $totalPayment += $payment;
//            $totalRemaining += $remaining;
            }
        }

        $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("E{$counter}", 'TOTAL');
        $worksheet->setCellValue("F{$counter}", CHtml::encode($grandTotalInvoice));
//        $worksheet->setCellValue("E{$counter}", CHtml::encode($totalPayment));
//        $worksheet->setCellValue("F{$counter}", CHtml::encode($totalRemaining));

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pelunasan Penjualan Detail.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}
