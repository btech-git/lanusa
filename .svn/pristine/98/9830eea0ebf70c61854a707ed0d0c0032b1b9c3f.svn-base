<?php

class PurchaseReceiptSummaryController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlSupplier') {
            if (!(Yii::app()->user->checkAccess('purchaseReceiptReport') ))
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
        $suppliers = Supplier::model()->findAllByAttributes(array('branch_id' => $branchId), array('order' => 'name ASC'));

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

    protected function saveToExcel($saleReceiptSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Tanda Terima Pembelian Detail');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Tanda Terima Pembelian Detail');

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
        $worksheet->setCellValue('A2', 'Laporan Tanda Terima Pembelian Detail');
        $worksheet->setCellValue('A3', '');

        $worksheet->getStyle('A5:G5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanda Terima');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'PO');
        $worksheet->setCellValue('D5', 'Tanggal PO');
        $worksheet->setCellValue('E5', 'Supplier');
        $worksheet->setCellValue('F5', 'Total PO');
        $worksheet->setCellValue('G5', 'Catatan');

        $worksheet->getStyle('A5:G5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;
        $grandTotalInvoice = 0.00;
        foreach ($dataProvider->data as $header) {
            foreach ($header->purchaseReceiptDetails as $detail) {
                $totalInvoice = CHtml::value($detail, 'receiveHeader.grandTotalReceipt');
                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode($detail->receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT)));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->receiveHeader->date))));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'supplier.company')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode($totalInvoice));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($header, 'note')));
                $counter++;

                $grandTotalInvoice += $totalInvoice;
            }
        }

        $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("E{$counter}", 'TOTAL');
        $worksheet->setCellValue("F{$counter}", CHtml::encode($grandTotalInvoice));

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pelunasan Pembelian Detail.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}
