<?php

class PurchaseController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'saveToExcel'
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

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
        $branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';

        $branch = Branch::model()->findByPk($branchId);

        $suppliers = Supplier::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'name ASC'
        ));

        $purchaseSummary = new PurchaseSummary($purchaseHeader->search());
        $purchaseSummary->setupLoading();
        $purchaseSummary->setupPaging($pageSize, $currentPage);
        $purchaseSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $purchaseSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($purchaseSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate, 'branchId' => $branchId));
        }

        $this->render('summary', array(
            'purchaseHeader' => $purchaseHeader,
            'purchaseSummary' => $purchaseSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'supplierId' => $supplierId,
            'branch' => $branch,
            'suppliers' => $suppliers
        ));
    }

    protected function saveToExcel($dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();
        $branchId = $options['branchId'];
        if (!empty($branchId)) {
            $branch = Branch::model()->findByPk($branchId);
        }

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pembelian Barang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Pembelian');

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');

        $worksheet->getStyle('A1:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G6')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', empty($branchId) ? 'PT Logam Nusantara' : $branch->name);
        $worksheet->setCellValue('A2', 'Pembelian Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:G5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->setCellValue('A5', 'Pembelian #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Supplier');
        $worksheet->setCellValue('D5', 'Catatan');
        $worksheet->setCellValue('A6', 'Nama Barang');
        $worksheet->setCellValue('B6', 'Ukuran');
        $worksheet->setCellValue('C6', 'Jumlah');
        $worksheet->setCellValue('D6', 'Satuan');
        $worksheet->setCellValue('E6', 'Harga Satuan');
        $worksheet->setCellValue('F6', 'Diskon');
        $worksheet->setCellValue('G6', 'Total');
        $worksheet->getStyle('A6:G6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {

            $worksheet->setCellValue("A{$counter}", $header->getCodeNumber(PurchaseHeader::CN_CONSTANT));
            $worksheet->setCellValue("B{$counter}", CHtml::value($header, 'date'));
            $worksheet->setCellValue("C{$counter}", CHtml::value($header, 'supplier.company'));
            $worksheet->setCellValue("D{$counter}", CHtml::value($header, 'note'));
            $counter++;
            
            foreach ($header->purchaseDetails as $detail) {
                $worksheet->setCellValue("A{$counter}", CHtml::value($detail, 'product.name'));
                $worksheet->setCellValue("B{$counter}", CHtml::value($detail, 'product.size'));
                $worksheet->setCellValue("C{$counter}", CHtml::value($detail, 'quantity'));
                $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'product.unit.name'));
                $worksheet->setCellValue("E{$counter}", CHtml::value($detail, 'unit_price'));
                $worksheet->setCellValue("F{$counter}", CHtml::value($detail, 'discount'));
                $worksheet->setCellValue("G{$counter}", CHtml::value($detail, 'total'));

                $counter++;
            }
            
            $worksheet->getStyle("f{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            
            $worksheet->setCellValue("F{$counter}", 'Sub Total');
            $worksheet->setCellValue("G{$counter}", round(CHtml::value($header, 'subTotal'), 2));

            $counter++;
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Diskon');
            $worksheet->setCellValue("G{$counter}", CHtml::value($header, 'discount'));

            $counter++;
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'PPn ' . CHtml::value($header, 'tax') . '%');
            $worksheet->setCellValue("G{$counter}", round(CHtml::value($header, 'calculatedTax'), 2));

            $counter++;
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Ongkos Kirim');
            $worksheet->setCellValue("G{$counter}", CHtml::value($header, 'shipping_fee'));

            $counter++;
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Grand Total');
            $worksheet->setCellValue("G{$counter}", round(CHtml::value($header, 'grandTotal'), 2));

            $counter++;
            $counter++;
        }

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }
        
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan Pembelian Barang.xls"');
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
}