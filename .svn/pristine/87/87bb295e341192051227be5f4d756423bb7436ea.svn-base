<?php

class StockGlobalController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('stockReport'))) {
                $this->redirect(array('/site/login'));
            }
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $listDataCategory = CHtml::listData(Category::model()->findAll(array('order' => 'name ASC')), 'id', 'name');

        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
        $category = (isset($_GET['category'])) ? $_GET['category'] : '';

        $stockGlobalSummary = new StockGlobalSummary($product->searchByGlobalStock());
        $stockGlobalSummary->setupLoading();
        $stockGlobalSummary->setupPaging($pageSize, $currentPage);
        $stockGlobalSummary->setupSorting();
        $stockGlobalSummary->setupFilter($category);

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($stockGlobalSummary, $stockGlobalSummary->dataProvider);
        }

        $this->render('summary', array(
            'product' => $product,
            'stockGlobalSummary' => $stockGlobalSummary,
            'currentSort' => $currentSort,
            'category' => $category,
            'listDataCategory' => $listDataCategory,
        ));
    }

    protected function reportTotalStock($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->getGlobalStock();
        }

        return $grandTotal;
    }

    protected function reportTotalStockPrice($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data) {
            $grandTotal += $data->getGlobalStockPrice();
        }

        return $grandTotal;
    }

    protected function saveToExcel($stockGlobalSummary, $dataProvider) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Stok');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Stok');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);

        $worksheet->mergeCells('A1:F1');
        $worksheet->mergeCells('A2:F2');
        $worksheet->mergeCells('A3:F3');

        $worksheet->getStyle('A1:F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F5')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', '');
        $worksheet->setCellValue('A2', 'Laporan Stok Barang Global');
        $worksheet->setCellValue('A3', '');

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Kategori');
        $worksheet->setCellValue('B5', 'Nama Produk');
        $worksheet->setCellValue('C5', 'Ukuran');
        $worksheet->setCellValue('D5', 'Stok');
        $worksheet->setCellValue('E5', 'Hrg Average');
        $worksheet->setCellValue('F5', 'Total');

        $worksheet->getStyle('A5:F5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 6;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($header, 'category.name')));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($header, 'name')));
            $worksheet->setCellValue("C{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($header, 'size'))));
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->getGlobalStock()));
            $worksheet->setCellValue("E{$counter}", CHtml::encode($header->getGlobalStockItemPrice()));
            $worksheet->setCellValue("F{$counter}", CHtml::encode($header->getGlobalStockPrice()));
            $counter++;
        }


        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", 'TOTAL');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($this->reportTotalStock($stockGlobalSummary->dataProvider)));
        $worksheet->setCellValue("F{$counter}", CHtml::encode($this->reportTotalStockPrice($stockGlobalSummary->dataProvider)));

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Stok Barang Global.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
