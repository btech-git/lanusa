<?php

class PurchaseItemsController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('purchaseReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);

        $purchaseItemSummary = new PurchaseItemSummary($product->search());
        $purchaseItemSummary->setupLoading();
        $purchaseItemSummary->setupPaging($pageSize, $currentPage);
        $purchaseItemSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'productName' => CHtml::value($product, 'name'),
            'productCategoryId' => CHtml::value($product, 'productCategory.id')
        );
        $purchaseItemSummary->setupFilter($filters);

        if (isset($_GET['SaveToExcel'])) {
            $this->saveToExcel($purchaseItemSummary, $startDate, $endDate, $branch);
        }

        $this->render('summary', array(
            'product' => $product,
            'purchaseItemSummary' => $purchaseItemSummary,
            'branchId' => $branchId,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort
        ));
    }

    protected function saveToExcel($purchaseItemSummary, $startDate, $endDate, $branch) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        $startDateString = Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate);
        $endDateString = Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate);

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pembelian Per Item');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Pembelian Per Item');

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');
        $worksheet->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:G3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', $branch->name);
        $worksheet->setCellValue('A2', 'Laporan Pembelian Per Item');
        $worksheet->setCellValue('A3', $startDateString . ' - ' . $endDateString);

        $worksheet->mergeCells('A4:G4');
        $worksheet->mergeCells('A5:G5');

        $worksheet->getStyle("A6:G7")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:G7")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:G7')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Kategori');
        $worksheet->setCellValue('D6', 'Nama Produk');
        $worksheet->setCellValue('G6', 'Ukuran');

        $worksheet->setCellValue('A7', 'Pembelian #');
        $worksheet->setCellValue('B7', 'Tanggal');
        $worksheet->setCellValue('C7', 'Supplier');

        $worksheet->getStyle('D7:G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue('D7', 'Jumlah');
        $worksheet->setCellValue('E7', 'Harga');
        $worksheet->setCellValue('F7', 'Disc');
        $worksheet->setCellValue('G7', 'Total');

        $counter = 8;

        foreach ($purchaseItemSummary->dataProvider->data as $header) {

            $worksheet->setCellValue("A{$counter}", CHtml::value($header, 'category.name'));
            $worksheet->setCellValue("D{$counter}", CHtml::value($header, 'name'));
            $worksheet->setCellValue("G{$counter}", CHtml::value($header, 'size'));

            $counter++;
            $total = 0;
            $quantityTotal = 0;
            foreach ($header->purchaseDetails as $detail) {

                if ($detail->purchaseHeader !== null
                && $detail->purchaseHeader->date >= $startDate
                && $detail->purchaseHeader->date <= $endDate
                && $detail->purchaseHeader->branch_id == $branch->id
                ) {
                    //relation doesn't filter the attributes from filter
                    $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $worksheet->setCellValue("A{$counter}", ($detail->purchaseHeader !== null) ? CHtml::encode($detail->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)) : 'No Purchase Header');
                    $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'purchaseHeader.date'))));
                    $worksheet->setCellValue("C{$counter}", CHtml::value($detail, 'purchaseHeader.supplier.company'));

                    $worksheet->getStyle("D{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'quantity'));
                    $worksheet->setCellValue("E{$counter}", CHtml::value($detail, 'unitPrice'));
                    $worksheet->setCellValue("F{$counter}", CHtml::value($detail, 'discount'));
                    $worksheet->setCellValue("G{$counter}", CHtml::value($detail, 'totalReport'));


                    $quantityTotal += CHtml::value($detail, 'quantity');
                    $total += CHtml::value($detail, 'totalReport');

                    $counter++;
                }
            }
            $worksheet->getStyle("A{$counter}:G{$counter}")->getFont()->setBold(true);

            $worksheet->getStyle("A{$counter}:G{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("G{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("C{$counter}", 'Total');
            $worksheet->setCellValue("D{$counter}", ceil($quantityTotal));
            $worksheet->setCellValue("G{$counter}", ceil($total));

            $counter++;
            $counter++;
        }

        $counter++;


        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penjualan Per Item.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
