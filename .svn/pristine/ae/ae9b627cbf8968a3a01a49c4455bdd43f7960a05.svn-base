<?php

class PurchaseReceiptController extends Controller {

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

        $purchaseReceipt = Search::bind(new PurchaseReceiptHeader('search'), isset($_GET['PurchaseReceiptHeader']) ? $_GET['PurchaseReceiptHeader'] : array());

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

        $purchaseReceiptSummary = new PurchaseReceiptSummary($purchaseReceipt->search());
        $purchaseReceiptSummary->setupLoading();
        $purchaseReceiptSummary->setupPaging($pageSize, $currentPage);
        $purchaseReceiptSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $purchaseReceiptSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($purchaseReceiptSummary, $branch, $purchaseReceiptSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'purchaseReceipt' => $purchaseReceipt,
            'purchaseReceiptSummary' => $purchaseReceiptSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'branch' => $branch,
            'supplierId' => $supplierId,
            'suppliers' => $suppliers
        ));
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

    protected function saveToExcel($purchaseReceiptSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Tanda Terima');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Tanda Terima');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);

        $worksheet->mergeCells('A1:E1');
        $worksheet->mergeCells('A2:E2');
        $worksheet->mergeCells('A3:E3');

        $worksheet->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:E7')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Tanda Terima Pembelian');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:E5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanda Terima Pembelian #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Supplier');
        $worksheet->mergeCells('D5:E5');
        $worksheet->setCellValue('D5', 'Catatan');

        $worksheet->setCellValue('A6', 'PO #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->mergeCells('B6:C6');
        $worksheet->setCellValue('D6', 'Total');
        $worksheet->setCellValue('E6', 'Memo');

        $worksheet->setCellValue('A7', 'Pembayaran #');
        $worksheet->setCellValue('B7', 'Tanggal');
        $worksheet->mergeCells('B7:C7');
        $worksheet->setCellValue('D7', 'Total');
        $worksheet->setCellValue('E7', 'Total Hutang');

        $worksheet->getStyle('A6:E7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 8;
        $totalReceipt = 0.00;
        $grandTotalPayment = 0.00;
        $grandTotalReceive = 0.00;
        $grandTotalDebit = 0.00;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, 'supplier.company')));
            $worksheet->mergeCells("D{$counter}:E{$counter}");
            $worksheet->setCellValue("D{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));

            $counter++;

            foreach ($header->purchaseReceiptDetails as $detail) {
                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->receiveHeader->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)));
                $worksheet->mergeCells("B{$counter}:C{$counter}");
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->receiveHeader->purchaseHeader->date))));
//                                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'receiveHeader.purchaseHeader.supplier.company')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'receiveHeader.grandTotalReceipt')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));

                $counter++;
            }

            $worksheet->getStyle("D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("C{$counter}", 'Total');
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->totalReceive));
            if ($header->purchasePaymentHeaders == null) {
                $worksheet->setCellValue("E{$counter}", CHtml::encode($header->totalReceive));
            }

            $counter++;
            $counter++;
            $totalReceipt += CHtml::value($header, 'totalReceive');
            $grandTotalReceive += $header->totalReceive;

            if ($header->purchasePaymentHeaders != null) {
                $totalPayment = 0.00;
                foreach ($header->purchasePaymentHeaders as $detail) {
                    $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->getCodeNumber(PurchasePaymentHeader::CN_CONSTANT)));
                    $worksheet->mergeCells("B{$counter}:C{$counter}");
                    $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))));
                    //                                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'receiveHeader.purchaseHeader.supplier.company')));
                    $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'amountPaid')));
                    $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));

                    $counter++;
                    $totalPayment += $detail->amountPaid;
                }

                $totalDebit = $header->totalReceive - $totalPayment;
                $grandTotalPayment += $totalPayment;
                $grandTotalDebit += $totalDebit;

                $worksheet->getStyle("D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
                $worksheet->setCellValue("C{$counter}", 'Total');
                $worksheet->setCellValue("D{$counter}", CHtml::encode($totalPayment));
                $worksheet->setCellValue("E{$counter}", CHtml::encode($totalDebit));
                $counter++;
                $counter++;
            } else {
                $grandTotalDebit += $header->totalReceive;
            }
        }


        $worksheet->getStyle("A{$counter}:E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("C{$counter}:D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("C{$counter}:D{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("C{$counter}", 'GRAND TOTAL PEMBELIAN');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($grandTotalReceive));
        $counter++;

        $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("C{$counter}:E{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("C{$counter}", 'GRAND TOTAL PAYMENT');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($grandTotalPayment));
        $worksheet->setCellValue("E{$counter}", CHtml::encode($grandTotalDebit));
        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Tanda Terima Pembelian.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
