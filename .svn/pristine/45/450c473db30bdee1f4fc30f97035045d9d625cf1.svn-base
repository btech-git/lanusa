<?php

class SaleItemController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('saleReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
		ini_set('memory_limit', '1024M');
       
        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $customerCompany = isset($_GET['CustomerCompany']) ? $_GET['CustomerCompany'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
        $listDataCategory = CHtml::listData(Category::model()->findAll(array('order' => 'name ASC')), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $saleItemSummary = new SaleItemSummary($product->search());
        $saleItemSummary->setupLoading();
        $saleItemSummary->setupPaging($pageSize, $currentPage);
        $saleItemSummary->setupSorting();
        $saleItemSummary->setupFilter($startDate, $endDate, $category, $branch, $customerCompany);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleItemSummary, $branch, $saleItemSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'product' => $product,
            'saleItemSummary' => $saleItemSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'category' => $category,
            'customerCompany' => $customerCompany,
            'listData' => $listData,
            'listDataCategory' => $listDataCategory,
        ));
    }

    protected function saveToExcel($saleItemSummary, $branch, $dataProvider, array $options = array()) {
        $startDate = (empty($options['startDate'])) ? date('Y-m-d') : $options['startDate'];
        $endDate = (empty($options['endDate'])) ? date('Y-m-d') : $options['endDate'];
        
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penjualan Barang Berdasarkan Produk');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penjualan Berdasarkan Produk');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);


        $worksheet->mergeCells('A1:F1');
        $worksheet->mergeCells('A2:F2');
        $worksheet->mergeCells('A3:F3');

        $worksheet->getStyle('A1:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F6')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', '');
        $worksheet->setCellValue('A2', 'Laporan Penjualan Barang Berdasarkan Produk');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Kategori');
        $worksheet->mergeCells('B5:D5');
        $worksheet->setCellValue('B5', 'Nama Produk');
        $worksheet->setCellValue('E5', 'Ukuran');

        $worksheet->setCellValue('A6', 'Penjualan');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Pelanggan');
        $worksheet->setCellValue('D6', 'Jumlah');
        $worksheet->setCellValue('E6', 'Harga');
        $worksheet->setCellValue('F6', 'Total');

        $worksheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($header, 'category.name')));
            $worksheet->mergeCells("B{$counter}:D{$counter}");
            $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($header, 'name')));
            $worksheet->setCellValue("E{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($header, 'size'))));

            $counter++;

            $totalQuantitySales = 0;
            $totalSales = 0.00;
            foreach ($header->saleDetails as $detail) {
                $saleHeader = $detail->saleHeader(array('scopes' => 'resetScope', 'with' => 'customer:resetScope'));

                if ($detail->saleHeader !== null
                        && $detail->saleHeader->date >= $startDate
                        && $detail->saleHeader->date <= $endDate
                        && $detail->saleHeader->branch_id == $branch
                ):

                    $worksheet->setCellValue("A{$counter}", ($detail->saleHeader != NULL) ? CHtml::encode($saleHeader->getCodeNumber(SaleHeader::CN_CONSTANT)) : 'No Sale Header');
                    $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleHeader, 'date')))));
                    $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($saleHeader, 'customer.company')));
                    $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'quantity')));
                    $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'unit_price')));
                    $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'total')));

                    $totalQuantitySales += CHtml::value($detail, 'quantity');
                    $totalSales += CHtml::value($detail, 'total');

                    $counter++;

                endif;
            }

            $worksheet->getStyle("D{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("C{$counter}", 'Total');
            $worksheet->setCellValue("D{$counter}", CHtml::encode(ceil($totalQuantitySales)));
            $worksheet->setCellValue("F{$counter}", CHtml::encode(ceil($totalSales)));
            $counter++;
            $counter++;
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penjualan Per Item.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
