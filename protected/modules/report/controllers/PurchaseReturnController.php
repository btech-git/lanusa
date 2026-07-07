<?php

class PurchaseReturnController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlSupplier') {
            if (!(Yii::app()->user->checkAccess('purchaseReturnReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $purchaseReturnHeader = Search::bind(new PurchaseReturnHeader('search'), isset($_GET['PurchaseReturnHeader']) ? $_GET['PurchaseReturnHeader'] : array());

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

        $purchaseReturnSummary = new PurchaseReturnSummary($purchaseReturnHeader->search());
        $purchaseReturnSummary->setupLoading();
        $purchaseReturnSummary->setupPaging($pageSize, $currentPage);
        $purchaseReturnSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $purchaseReturnSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($purchaseReturnSummary, $branch, $purchaseReturnSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'purchaseReturnHeader' => $purchaseReturnHeader,
            'purchaseReturnSummary' => $purchaseReturnSummary,
            'branch' => $branch,
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
            $grandTotal += $data->grandTotal;

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

    protected function saveToExcel($purchaseReturnSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Retur Pembelian');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Retur Pembelian');

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

        $worksheet->getStyle('A1:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G6')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Retur Pembelian Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:G5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


        $worksheet->setCellValue('A5', 'Retur #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->mergeCells('C5:D5');
        $worksheet->setCellValue('C5', 'Penerimaan Faktur');
        $worksheet->setCellValue('E5', 'Supplier');
        $worksheet->mergeCells('F5:G5');
        $worksheet->setCellValue('F5', 'Catatan');

        $worksheet->mergeCells('A6:B6');
        $worksheet->setCellValue('A6', 'Nama Barang');
        $worksheet->setCellValue('C6', 'Ukuran');
        $worksheet->setCellValue('D6', 'Jumlah');
        $worksheet->setCellValue('E6', 'Satuan');
        $worksheet->setCellValue('F6', 'Harga');
        $worksheet->setCellValue('G6', 'Total');

        $worksheet->getStyle('A6:G6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->mergeCells("C{$counter}:D{$counter}");
            $worksheet->setCellValue("C{$counter}", '');
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'receiveHeader.purchaseHeader.supplier.name' : 'receiveHeader.purchaseHeader.supplier.company')));
            $worksheet->mergeCells("F{$counter}:G{$counter}");
            $worksheet->setCellValue("F{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));

            $counter++;


            foreach ($header->purchaseReturnDetails as $detail) {

                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("F{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->mergeCells("A{$counter}:B{$counter}");
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'product.name')));
                $worksheet->setCellValue("C{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($detail, 'product.size'))));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'quantity')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'product.unit.name')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'unitPrice')));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'total')));
                $counter++;
            }

            $worksheet->getStyle("G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Sub Total');
            $worksheet->setCellValue("G{$counter}", CHtml::encode(floor(CHtml::value($header, 'subTotal'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Tax' . CHtml::encode(floor(CHtml::value($header, 'tax'))) . '%');
            $worksheet->setCellValue("G{$counter}", CHtml::encode(floor(CHtml::value($header, 'calculatedTax'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Ongkos Kirim');
            $worksheet->setCellValue("G{$counter}", CHtml::encode(floor(CHtml::value($header, 'shipping_fee'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("F{$counter}", 'Grand Total');
            $worksheet->setCellValue("G{$counter}", CHtml::encode(floor(CHtml::value($header, 'grandTotal'))));
            $counter++;
            $counter++;
        }

        $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("F{$counter}", 'TOTAL RETUR');
        $worksheet->setCellValue("G{$counter}", CHtml::encode(ceil($this->reportGrandTotal($purchaseReturnSummary->dataProvider))));
        $counter++;
        
        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Retur Pembelian.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
