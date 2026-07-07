<?php

class ReceivableDetailController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('receivableReport') ))
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

        $receivableDetailSummary = new ReceivableDetailSummary($saleReceipt->search());
        $receivableDetailSummary->setupLoading();
        $receivableDetailSummary->setupPaging($pageSize, $currentPage);
        $receivableDetailSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'customerId' => $customerId
        );
        $receivableDetailSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($receivableDetailSummary, $branch, $receivableDetailSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'saleReceipt' => $saleReceipt,
            'receivableDetailSummary' => $receivableDetailSummary,
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
    
    protected function saveToExcel($receivableDetailSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Piutang Detail');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Piutang Detail');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);

        $worksheet->mergeCells('A1:F1');
        $worksheet->mergeCells('A2:F2');
        $worksheet->mergeCells('A3:F3');

        $worksheet->getStyle('A1:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F7')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Piutang Detail');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanda Terima Penjualan #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Customer');
        $worksheet->mergeCells('D5:F5');
        $worksheet->setCellValue('D5', 'Catatan');

        $worksheet->setCellValue('A6', 'Faktur');
        $worksheet->mergeCells('B6:C6');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('D6', 'Total');
        $worksheet->setCellValue('E6', 'Memo');
        $worksheet->setCellValue('F6', 'PO #');

        $worksheet->setCellValue('A7', 'Pembayaran');
        $worksheet->mergeCells('B7:C7');
        $worksheet->setCellValue('B7', 'Tanggal');
        $worksheet->setCellValue('D7', 'Total');
        $worksheet->setCellValue('E7', 'Total Piutang');

        $worksheet->getStyle('A7:F7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 8;
        $totalReceipt = 0.00;
        $grandTotalPayment = 0.00;
        $grandTotalInvoice = 0.00;
        $grandTotalCredit = 0.00;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, 'customer.company')));
            $worksheet->mergeCells("D{$counter}:F{$counter}");
            $worksheet->setCellValue("D{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));

            $counter++;

            foreach ($header->saleReceiptDetails as $detail) {
                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->saleInvoice ? $detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT) : ''));
                $worksheet->mergeCells("B{$counter}:C{$counter}");
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->saleInvoice->date))));
//                                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'saleInvoice.deliveryHeader.saleHeader.customer.company')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'saleInvoice.grandTotal')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'saleInvoice.deliveryHeader.saleHeader.reference')));

                $counter++;
            }

            $worksheet->getStyle("D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("C{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("C{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("C{$counter}", 'Total');
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->totalInvoice));
            if ($header->salePaymentHeaders == null) {
                $worksheet->setCellValue("E{$counter}", CHtml::encode($header->totalInvoice));
            }

            $counter++;
            $counter++;
            $totalReceipt += CHtml::value($header, 'totalInvoice');
            $grandTotalInvoice += $header->totalInvoice;

            if ($header->salePaymentHeaders != null) {
                $totalPayment = 0.00;
                foreach ($header->salePaymentHeaders as $detail) {
                    $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->getCodeNumber(SalePaymentHeader::CN_CONSTANT)));
                    $worksheet->mergeCells("B{$counter}:C{$counter}");
                    $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))));
                    //                                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'saleInvoice.deliveryHeader.saleHeader.customer.company')));
                    $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'amountPaid')));
//                    $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));

                    $counter++;
                    $totalPayment += $detail->amountPaid;
                }

                $totalCredit = $header->totalInvoice - $totalPayment;
                $grandTotalPayment += $totalPayment;
                $grandTotalCredit += $totalCredit;
                $worksheet->getStyle("D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
                $worksheet->setCellValue("C{$counter}", 'Total');
                $worksheet->setCellValue("D{$counter}", CHtml::encode($totalPayment));
                $worksheet->setCellValue("E{$counter}", CHtml::encode($totalCredit));
                $counter++;
                $counter++;
            } else {
                $grandTotalCredit += $header->totalInvoice;
            }
        }

        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("C{$counter}:D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("C{$counter}:D{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("C{$counter}", 'GRAND TOTAL INVOICE');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($grandTotalInvoice));
        $counter++;

        $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("C{$counter}", 'GRAND TOTAL PAYMENT');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($grandTotalPayment));
        $worksheet->setCellValue("E{$counter}", CHtml::encode($grandTotalCredit));
        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Piutang Detail.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
