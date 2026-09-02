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

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
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

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($receivableDetailSummary, $branch, array('startDate' => $startDate, 'endDate' => $endDate));
        }

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
    
    protected function saveToExcel($receivableDetailSummary, $branch, array $options = array()) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Piutang Detail');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Piutang Detail');

        $worksheet->mergeCells('A1:I1');
        $worksheet->mergeCells('A2:I2');
        $worksheet->mergeCells('A3:I3');

        $worksheet->getStyle('A1:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:I7')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::value($branch, 'name'));
        $worksheet->setCellValue('A2', 'Laporan Piutang Detail');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:I5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanda Terima #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Jatuh Tempo');
        $worksheet->setCellValue('D5', 'Umur (hari)');
        $worksheet->setCellValue('E5', 'Customer');
        $worksheet->setCellValue('F5', 'Catatan');
        $worksheet->setCellValue('G5', 'Total');
        $worksheet->setCellValue('H5', 'Pelunasan');
        $worksheet->setCellValue('I5', 'Sisa');

        $worksheet->setCellValue('A6', 'Faktur');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('D6', 'Total');
        $worksheet->setCellValue('E6', 'Memo');
        $worksheet->setCellValue('F6', 'PO #');

        $worksheet->setCellValue('A7', 'Pembayaran');
        $worksheet->setCellValue('B7', 'Tanggal');
        $worksheet->setCellValue('D7', 'Total');
        $worksheet->setCellValue('E7', 'Total Piutang');

        $worksheet->getStyle('A7:I7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 8;
        $totalReceipt = '0.00';
        $grandTotalPayment = '0.00';
        $grandTotalInvoice = '0.00';
        $grandTotalCredit = '0.00';
        foreach ($receivableDetailSummary->dataProvider->data as $header) {
            if (strtotime($header->date) < strtotime('2 months ago')) {
                $worksheet->getStyle("A{$counter}:I{$counter}")->getFont()->getColor()->setARGB('FFFF0000');
            }
            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber(SaleReceiptHeader::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date)));
            $worksheet->setCellValue("C{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->due_date)));
            $outstandingDays = date_diff(date_create($header->date), date_create(date('Y-m-d')));
            $worksheet->setCellValue("D{$counter}", $outstandingDays->format("%a days"));
            $worksheet->setCellValue("E{$counter}", CHtml::value($header, 'customer.company'));
            $worksheet->setCellValue("F{$counter}", nl2br(CHtml::value($header, 'note')));
            $worksheet->setCellValue("G{$counter}", CHtml::value($header, 'grand_total'));
            $worksheet->setCellValue("H{$counter}", CHtml::value($header, 'total_payment'));
            $worksheet->setCellValue("I{$counter}", CHtml::value($header, 'remaining'));

            $counter++;

            foreach ($header->saleReceiptDetails as $detail) {

                $worksheet->setCellValue("A{$counter}", $detail->saleInvoice ? $detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT) : '');
                $worksheet->mergeCells("B{$counter}:C{$counter}");
                $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($detail->saleInvoice->date)));
                $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'saleInvoice.grandTotal'));
                $worksheet->setCellValue("E{$counter}", CHtml::value($detail, 'memo'));
                $worksheet->setCellValue("F{$counter}", CHtml::value($detail, 'saleInvoice.deliveryHeader.saleHeader.reference'));

                $counter++;
            }

            $worksheet->getStyle("D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("C{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("C{$counter}:F{$counter}")->getFont()->setBold(true);
            
            $worksheet->setCellValue("C{$counter}", 'Total');
            $worksheet->setCellValue("D{$counter}", $header->totalInvoice);
            if ($header->salePaymentHeaders == null) {
                $worksheet->setCellValue("E{$counter}", $header->totalInvoice);
            }

            $counter++;
            $counter++;
            $totalReceipt += CHtml::value($header, 'totalInvoice');
            $grandTotalInvoice += $header->totalInvoice;

            if ($header->salePaymentHeaders != null) {
                $totalPayment = '0.00';
                foreach ($header->salePaymentHeaders as $detail) {
                    $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $worksheet->setCellValue("A{$counter}", $detail->getCodeNumber(SalePaymentHeader::CN_CONSTANT));
                    $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date)));
                    $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'amountPaid'));

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
                $worksheet->setCellValue("D{$counter}", $totalPayment);
                $worksheet->setCellValue("E{$counter}", $totalCredit);
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
        $worksheet->setCellValue("D{$counter}", $grandTotalInvoice);
        $counter++;

        $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("C{$counter}", 'GRAND TOTAL PAYMENT');
        $worksheet->setCellValue("D{$counter}", $grandTotalPayment);
        $worksheet->setCellValue("E{$counter}", $grandTotalCredit);
        $counter++;

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment;filename="Laporan Piutang Detail.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
