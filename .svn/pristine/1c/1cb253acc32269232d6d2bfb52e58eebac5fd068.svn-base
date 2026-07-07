<?php

class SalePaymentController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('salePaymentReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->amountPaid;

        return $grandTotal;
    }

    protected function reportTotalReceipt($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->totalSale;

        return $grandTotal;
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $salePaymentHeader = Search::bind(new SalePaymentHeader('search'), isset($_GET['SalePaymentHeader']) ? $_GET['SalePaymentHeader'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);
        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';
        $customers = Customer::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'name ASC'
        )
        );
        $salePaymentSummary = new SalePaymentSummary($salePaymentHeader->search());
        $salePaymentSummary->setupLoading();
        $salePaymentSummary->setupPaging($pageSize, $currentPage);
        $salePaymentSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'customerId' => $customerId
        );
        $salePaymentSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($salePaymentSummary, $branch, $salePaymentSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'salePaymentHeader' => $salePaymentHeader,
            'salePaymentSummary' => $salePaymentSummary,
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

    protected function saveToExcel($salePaymentSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pembayaran Penjualan');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Pembayaran Penjualan');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);
        $worksheet->getColumnDimension('G')->setAutoSize(true);
        $worksheet->getColumnDimension('H')->setAutoSize(true);
        $worksheet->getColumnDimension('I')->setAutoSize(true);
        $worksheet->getColumnDimension('J')->setAutoSize(true);
        $worksheet->getColumnDimension('K')->setAutoSize(true);

        $worksheet->mergeCells('A1:K1');
        $worksheet->mergeCells('A2:K2');
        $worksheet->mergeCells('A3:K3');

        $worksheet->getStyle('A1:K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:K5')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Pembayaran Penjualan');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:K5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'TT  #');
        $worksheet->setCellValue('B5', 'Tanggal TT');
        $worksheet->setCellValue('C5', 'Pembayaran #');
        $worksheet->setCellValue('D5', 'Tanggal');
        $worksheet->setCellValue('E5', 'Customer');
        $worksheet->setCellValue('F5', 'Catatan');
        $worksheet->setCellValue('G5', 'Nama Akun');
        $worksheet->setCellValue('H5', 'Jenis Pembayaran');
        $worksheet->setCellValue('I5', 'Memo');
        $worksheet->setCellValue('J5', 'Total TT');
        $worksheet->setCellValue('K5', 'Jumlah Bayar');

        $worksheet->getStyle('A5:K5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;

        foreach ($dataProvider->data as $header) {
//            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            foreach ($header->salePaymentDetails as $detail) {
                $worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $worksheet->mergeCells("D{$counter}:F{$counter}");

                $worksheet->setCellValue("A{$counter}", CHtml::encode($header->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->saleReceiptHeader->date))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode($header->getCodeNumber(SalePaymentHeader::CN_CONSTANT)));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'saleReceiptHeader.customer.company')));
                $worksheet->setCellValue("F{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'account.name')));
                $worksheet->setCellValue("H{$counter}", CHtml::encode(CHtml::value($detail, 'paymentType.name')));
                $worksheet->setCellValue("I{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));
                $worksheet->setCellValue("J{$counter}", CHtml::encode($header->saleReceiptHeader->totalInvoice));
                $worksheet->setCellValue("K{$counter}", CHtml::encode($detail->amount));

                $counter++;
            }

//            $worksheet->getStyle("C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//
//            $worksheet->getStyle("B{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//            $worksheet->getStyle("B{$counter}:C{$counter}")->getFont()->setBold(true);
//            $worksheet->setCellValue("B{$counter}", 'Total');
//            $worksheet->setCellValue("C{$counter}", CHtml::encode(ceil($header->totalSale)));
//            $counter++;
        }

        $worksheet->getStyle("I{$counter}:K{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("I{$counter}:K{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("I{$counter}:K{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("I{$counter}", 'Total');
        $worksheet->setCellValue("J{$counter}", CHtml::encode(ceil($this->reportTotalReceipt($salePaymentSummary->dataProvider))));
        $worksheet->setCellValue("K{$counter}", CHtml::encode(ceil($this->reportGrandTotal($salePaymentSummary->dataProvider))));
        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pembayaran Penjualan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
