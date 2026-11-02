<?php

class SaleItemController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('saleReport'))) {
                $this->redirect(array('/site/login'));
            }
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
       
        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        
        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $categoryId = isset($_GET['CategoryId']) ? $_GET['CategoryId'] : '';
        $customerCompany = isset($_GET['CustomerCompany']) ? $_GET['CustomerCompany'] : '';
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
        $listDataCategory = CHtml::listData(Category::model()->findAll(array('order' => 'name ASC')), 'id', 'name');

        $saleItemSummary = new SaleItemSummary($product->search());
        $saleItemSummary->setupLoading();
        $saleItemSummary->setupPaging($pageSize, $currentPage);
        $saleItemSummary->setupSorting();
        $saleItemSummary->setupFilter($startDate, $endDate, $categoryId, $branchId, $customerCompany);

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($saleItemSummary, $branchId, $saleItemSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));
        }

        $this->render('summary', array(
            'product' => $product,
            'saleItemSummary' => $saleItemSummary,
            'branchId' => $branchId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'categoryId' => $categoryId,
            'customerCompany' => $customerCompany,
            'listData' => $listData,
            'listDataCategory' => $listDataCategory,
        ));
    }

    protected function saveToExcel($saleItemSummary, $branchId, $dataProvider, array $options = array()) {
        $startDate = (empty($options['startDate'])) ? date('Y-m-d') : $options['startDate'];
        $endDate = (empty($options['endDate'])) ? date('Y-m-d') : $options['endDate'];
        
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        if (!empty($branchId)) {
            $branch = Branch::model()->findByPk($branchId);
        }

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penjualan Barang Berdasarkan Produk');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penjualan Berdasarkan Produk');

        $worksheet->mergeCells('A1:J1');
        $worksheet->mergeCells('A2:J2');
        $worksheet->mergeCells('A3:J3');

        $worksheet->getStyle('A1:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:J5')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', empty($branchId) ? 'PT Logam Nusantara' : $branch->name);
        $worksheet->setCellValue('A2', 'Laporan Penjualan Barang Berdasarkan Produk');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:J5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Penjualan #');
        $worksheet->setCellValue('B5', 'Kategori');
        $worksheet->setCellValue('C5', 'Tanggal');
        $worksheet->setCellValue('D5', 'Customer');
        $worksheet->setCellValue('E5', 'Nama Produk');
        $worksheet->setCellValue('F5', 'Ukuran');
        $worksheet->setCellValue('G5', 'Jumlah');
        $worksheet->setCellValue('H5', 'Discount');
        $worksheet->setCellValue('I5', 'Harga');
        $worksheet->setCellValue('J5', 'Total');

        $worksheet->getStyle('A5:J5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;
        foreach ($saleItemSummary->dataProvider->data as $header) {
//            $totalQuantitySales = 0;
//            $totalSales = '0.00';
            foreach ($header->saleDetails as $detail) {
                $saleHeader = $detail->saleHeader(array('scopes' => 'resetScope', 'with' => 'customer:resetScope'));

                if (
                    $detail->saleHeader !== null && $detail->saleHeader->date >= $startDate && $detail->saleHeader->date <= $endDate && 
                    $detail->saleHeader->branch_id == $branchId
                ){
                    $quantity = CHtml::value($detail, 'quantity');
                    $totalDetail = CHtml::value($detail, 'total');
                    
                    $worksheet->setCellValue("A{$counter}", $detail->saleHeader != NULL ? $saleHeader->getCodeNumber(SaleHeader::CN_CONSTANT) : 'No Sale Header');
                    $worksheet->setCellValue("B{$counter}", CHtml::value($header, 'category.name'));
                    $worksheet->setCellValue("C{$counter}", CHtml::value($saleHeader, 'date'));
                    $worksheet->setCellValue("D{$counter}", CHtml::value($saleHeader, 'customer.company'));
                    $worksheet->setCellValue("E{$counter}", CHtml::value($header, 'name'));
                    $worksheet->setCellValue("F{$counter}", htmlspecialchars_decode(CHtml::value($header, 'size')));
                    $worksheet->setCellValue("G{$counter}", $quantity);
                    $worksheet->setCellValue("H{$counter}", CHtml::value($detail, 'discount'));
                    $worksheet->setCellValue("I{$counter}", round(CHtml::value($detail, 'unit_price'), 2));
                    $worksheet->setCellValue("J{$counter}", round($totalDetail, 2));

//                    $totalQuantitySales += $quantity;
//                    $totalSales += $totalDetail;

                    $counter++;
                }
            }

//            $worksheet->getStyle("D{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
//            
//            $worksheet->setCellValue("C{$counter}", 'Total');
//            $worksheet->setCellValue("D{$counter}", ceil($totalQuantitySales)));
//            $worksheet->setCellValue("F{$counter}", ceil($totalSales)));
//            $counter++;
            $counter++;
        }

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }
        
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan Penjualan Per Item.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
