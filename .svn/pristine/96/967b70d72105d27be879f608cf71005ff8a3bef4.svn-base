<?php

class InventoryController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'saveToExcel') {
            if (!(Yii::app()->user->checkAccess('stockReport')))
                $this->redirect(array('/site/login'));
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
        $warehouseId = (isset($_GET['WarehouseId'])) ? $_GET['WarehouseId'] : '';

        $inventorySummary = new InventorySummary($product->searchInInventory());
        $inventorySummary->setupLoading($startDate, $endDate);
        $inventorySummary->setupPaging($pageSize, $currentPage);
        $inventorySummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'productName' => $product->name,
            'productSize' => $product->size,
            'productCategory' => $product->category_id,
            'warehouseId' => $warehouseId
        );
        $inventorySummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($inventorySummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'product' => $product,
            'inventorySummary' => $inventorySummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'warehouseId' => $warehouseId
        ));
    }

    protected function saveToExcel($dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Gudang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Gudang');

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

        $worksheet->mergeCells('A1:G1');
        $worksheet->mergeCells('A2:G2');
        $worksheet->mergeCells('A3:G3');
        $worksheet->mergeCells('A5:B5');
        $worksheet->mergeCells('C5:D5');
        $worksheet->mergeCells('E5:H5');

        $worksheet->getStyle('A1:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:J6')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', '');
        $worksheet->setCellValue('A2', 'Laporan Gudang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->setCellValue('A5', 'Kategori');
        $worksheet->setCellValue('C5', 'Nama Produk');
        $worksheet->setCellValue('E5', 'Ukuran');
        $worksheet->setCellValue('I5', 'Stok Awal');
        $worksheet->setCellValue('J5', 'Stok Akhir');

        $worksheet->getStyle('A5:J6')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle('A5:J6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle('A5:J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $worksheet->setCellValue('A6', 'Transaksi #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Jenis');
        $worksheet->setCellValue('D6', 'Pelaksana');
        $worksheet->setCellValue('E6', 'Jumlah Masuk');
        $worksheet->setCellValue('F6', 'Jumlah Keluar');
        $worksheet->setCellValue('G6', 'Avg Price');
        $worksheet->setCellValue('H6', 'Qty End');
        $worksheet->setCellValue('I6', 'Total');
        $worksheet->setCellValue('J6', 'Gudang');

        $counter = 8;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//            $worksheet->getStyle("E{$counter}")->getNumberFormat()->setFormatCode('#,##0');

            $worksheet->setCellValue("A{$counter}", $header->category->name);
            $worksheet->setCellValue("C{$counter}", $header->name);
            $worksheet->setCellValue("E{$counter}", $header->size);
            $worksheet->setCellValue("I{$counter}", $header->getInventoryReportStockFromBeginningTo($options['startDate']));
            $worksheet->setCellValue("J{$counter}", CHtml::encode($header->getInventoryReportStockFromBeginningTo($options['endDate'], true)));

            $counter++;
            $reportCurrentStock = $header->getInventoryReportStockFromBeginningTo($options['startDate']);
            foreach ($header->inventories as $detail) {
//                $worksheet->getStyle("I{$counter}:J{$counter}")->getNumberFormat()->setFormatCode('#,##0');
                $worksheet->setCellValue("A{$counter}", $detail->transaction_ordinal);
                $worksheet->setCellValue("B{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date)));
                $worksheet->setCellValue("C{$counter}", $detail->transaction_type);
                $worksheet->setCellValue("D{$counter}", $detail->transaction_subject);
                $worksheet->setCellValue("E{$counter}", $detail->quantity_in);
                $worksheet->setCellValue("F{$counter}", $detail->quantity_out);
                $worksheet->getStyle("G{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'price')));
                $worksheet->setCellValue("H{$counter}", CHtml::encode(($currentStock = $detail->getEndingQuantity($reportCurrentStock))));
                $worksheet->setCellValue("I{$counter}", CHtml::encode($detail->getEndingPrice($currentStock)));
                $worksheet->setCellValue("J{$counter}", $detail->warehouse->name);

                $counter++;
                $reportCurrentStock = $currentStock;
            }
            $counter++;
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="laporan gudang.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
