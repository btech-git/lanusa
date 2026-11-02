<?php

class PurchaseItemsController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('purchaseReport'))) {
                $this->redirect(array('/site/login'));
            }
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
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

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pembelian Per Item');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Pembelian Per Item');

        $worksheet->mergeCells('A1:J1');
        $worksheet->mergeCells('A2:J2');
        $worksheet->mergeCells('A3:J3');
        
        $worksheet->getStyle('A1:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:J5')->getFont()->setBold(true);
        
        $worksheet->setCellValue('A1', empty($branch) ? 'PT Logam Nusantara' : $branch->name);
        $worksheet->setCellValue('A2', 'Laporan Pembelian Per Item');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate));

        $worksheet->getStyle("A5:J5")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A5:J5")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Pembelian #');
        $worksheet->setCellValue('B5', 'Kategori');
        $worksheet->setCellValue('C5', 'Tanggal');
        $worksheet->setCellValue('D5', 'Supplier');
        $worksheet->setCellValue('E5', 'Nama Produk');
        $worksheet->setCellValue('F5', 'Ukuran');
        $worksheet->setCellValue('G5', 'Jumlah');
        $worksheet->setCellValue('H5', 'Discount');
        $worksheet->setCellValue('I5', 'Harga');
        $worksheet->setCellValue('J5', 'Total');

        $counter = 6;

        foreach ($purchaseItemSummary->dataProvider->data as $header) {
//            $totalPrice = '0.00';
//            $quantityTotal = 0;
            
            foreach ($header->purchaseDetails as $detail) {

                if (
                    $detail->purchaseHeader !== null && $detail->purchaseHeader->date >= $startDate && $detail->purchaseHeader->date <= $endDate && 
                    $detail->purchaseHeader->branch_id == $branch->id
                ) {
                    $quantity = CHtml::value($detail, 'quantity');
                    $totalDetail = CHtml::value($detail, 'totalReport');

                    $worksheet->setCellValue("A{$counter}", ($detail->purchaseHeader !== null) ? CHtml::encode($detail->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)) : 'No Purchase Header');
                    $worksheet->setCellValue("B{$counter}", CHtml::value($header, 'category.name'));
                    $worksheet->setCellValue("C{$counter}", CHtml::value($detail, 'purchaseHeader.date'));
                    $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'purchaseHeader.supplier.company'));
                    $worksheet->setCellValue("E{$counter}", CHtml::value($header, 'name'));
                    $worksheet->setCellValue("F{$counter}", CHtml::value($header, 'size'));
                    $worksheet->setCellValue("G{$counter}", $quantity);
                    $worksheet->setCellValue("H{$counter}", CHtml::value($detail, 'discount'));
                    $worksheet->setCellValue("I{$counter}", round(CHtml::value($detail, 'unitPrice'), 2));
                    $worksheet->setCellValue("J{$counter}", round($totalDetail, 2));

//                    $quantityTotal += $quantity;
//                    $totalPrice += $totalDetail;

                    $counter++;
                }
            }
//            $worksheet->getStyle("A{$counter}:J{$counter}")->getFont()->setBold(true);
//            $worksheet->getStyle("A{$counter}:J{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            
//            $worksheet->setCellValue("F{$counter}", 'Total');
//            $worksheet->setCellValue("G{$counter}", $quantityTotal);
//            $worksheet->setCellValue("J{$counter}", round($totalPrice, 2));
//
//            $counter++;
            $counter++;
        }

        $counter++;


        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan Penjualan Per Item.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}