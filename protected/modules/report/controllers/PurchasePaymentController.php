<?php

class PurchasePaymentController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlSupplier') {
            if (!(Yii::app()->user->checkAccess('purchasePaymentReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $purchasePaymentHeader = Search::bind(new PurchasePaymentHeader('search'), isset($_GET['PurchasePaymentHeader']) ? $_GET['PurchasePaymentHeader'] : array());
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);

        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';
        $suppliers = Supplier::model()->findAllByAttributes(
            array(
                'branch_id' => $branchId
            ), array(
                'order' => 'name ASC'
            )
        );

        $purchasePaymentSummary = new PurchasePaymentSummary($purchasePaymentHeader->search());
        $purchasePaymentSummary->setupLoading();
        $purchasePaymentSummary->setupPaging($pageSize, $currentPage);
        $purchasePaymentSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $purchasePaymentSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($purchasePaymentSummary, $branch, $purchasePaymentSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'purchasePaymentHeader' => $purchasePaymentHeader,
            'purchasePaymentSummary' => $purchasePaymentSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'branch' => $branch,
            'supplierId' => $supplierId,
            'suppliers' => $suppliers
        ));
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
            $grandTotal += $data->totalPurchase;

        return $grandTotal;
    }

    public function actionAjaxHtmlSupplier() {         //find supplier based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $supplierId = '';
            $branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';

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

    protected function saveToExcel($purchasePaymentSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pembayaran Pembelian');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Pembayaran Pembelian');

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
        $worksheet->setCellValue('A2', 'Laporan Pembayaran Pembelian');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:K5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'TT  #');
        $worksheet->setCellValue('B5', 'Tanggal TT');
        $worksheet->setCellValue('C5', 'Pembayaran #');
        $worksheet->setCellValue('D5', 'Tanggal');
        $worksheet->setCellValue('E5', 'Supplier');
        $worksheet->setCellValue('F5', 'Catatan');
        $worksheet->setCellValue('G5', 'Nama Akun');
        $worksheet->setCellValue('H5', 'Jenis Pembayaran');
        $worksheet->setCellValue('I5', 'Memo');
        $worksheet->setCellValue('J5', 'Total TT');
        $worksheet->setCellValue('K5', 'Jumlah Bayar');

        $worksheet->getStyle('A5:K5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;

        foreach ($dataProvider->data as $header) {
            foreach ($header->purchasePaymentDetails as $detail) {
                $worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($header->purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->purchaseReceiptHeader->date))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode($header->getCodeNumber(SalePaymentHeader::CN_CONSTANT)));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'purchaseReceiptHeader.supplier.company')));
                $worksheet->setCellValue("F{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'account.name')));
                $worksheet->setCellValue("H{$counter}", CHtml::encode(CHtml::value($detail, 'paymentType.name')));
                $worksheet->setCellValue("I{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));
                $worksheet->setCellValue("J{$counter}", CHtml::encode($header->purchaseReceiptHeader->grandTotal));
                $worksheet->setCellValue("K{$counter}", CHtml::encode($detail->amount));

                $counter++;
            }
        }

        $worksheet->getStyle("I{$counter}:K{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("I{$counter}:K{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("I{$counter}:K{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("I{$counter}", 'Total');
        $worksheet->setCellValue("J{$counter}", CHtml::encode(ceil($this->reportTotalReceipt($purchasePaymentSummary->dataProvider))));
        $worksheet->setCellValue("K{$counter}", CHtml::encode(ceil($this->reportGrandTotal($purchasePaymentSummary->dataProvider))));
        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pembayaran Pembelian.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}
