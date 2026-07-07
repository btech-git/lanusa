<?php

class DeliveryItemController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('deliveryReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(array('order' => 'name ASC')), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $deliveryItemSummary = new DeliveryItemSummary($product->search());
        $deliveryItemSummary->setupLoading();
        $deliveryItemSummary->setupPaging($pageSize, $currentPage);
        $deliveryItemSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branch' => $branch
        );
        $deliveryItemSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($deliveryItemSummary, $branch, $deliveryItemSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'product' => $product,
            'deliveryItemSummary' => $deliveryItemSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'listData' => $listData
        ));
    }

    protected function saveToExcel($deliveryItemSummary, $branch, $dataProvider, array $options = array()) {
        $startDate = (empty($options['startDate'])) ? date('Y-m-d') : $options['startDate'];
        $endDate = (empty($options['endDate'])) ? date('Y-m-d') : $options['endDate'];

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Pengiriman Barang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Pengiriman Barang');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);
        $worksheet->getColumnDimension('G')->setAutoSize(true);
        $worksheet->getColumnDimension('H')->setAutoSize(true);

        $worksheet->mergeCells('A1:H1');
        $worksheet->mergeCells('A2:H2');
        $worksheet->mergeCells('A3:H3');

        $worksheet->getStyle('A1:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:H6')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Pengiriman Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:H5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->mergeCells('A5:B5');
        $worksheet->setCellValue('A5', 'Kategori');
        $worksheet->mergeCells('D5:F5');
        $worksheet->setCellValue('D5', 'Nama Produk');
        $worksheet->mergeCells('G5:H5');
        $worksheet->setCellValue('G5', 'Ukuran');

        $worksheet->setCellValue('A6', 'Pengiriman #');
        $worksheet->setCellValue('B6', 'Tanggal');
        $worksheet->setCellValue('C6', 'Penjualan #');
        $worksheet->setCellValue('D6', 'Customer');
        $worksheet->setCellValue('E6', 'Jumlah');

        $worksheet->getStyle('A6:H6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $worksheet->mergeCells("A{$counter}:B{$counter}");
            $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($header, 'category.name')));
            $worksheet->mergeCells("D{$counter}:F{$counter}");
            $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($header, 'name')));
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->setCellValue("G{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($header, 'size'))));

            $counter++;

            $totalQuantity = 0;
            $totalPrice = 0.00;
            foreach ($header->deliveryDetails as $detail) {
                if ($detail->deliveryHeader !== null
                        && $detail->deliveryHeader->date >= $startDate
                        && $detail->deliveryHeader->date <= $endDate
                        && $detail->deliveryHeader->branch_id == $branch
                ):

                    $worksheet->getStyle("A{$counter}:D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $worksheet->getStyle("E{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT)));
                    $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'deliveryHeader.date')))));
                    $worksheet->setCellValue("C{$counter}", CHtml::encode($detail->deliveryHeader->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)));
                    $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'deliveryHeader.purchaseHeader.supplier.company')));
                    $worksheet->setCellValue("E{$counter}", CHtml::value($detail, 'quantity'));
                    $counter++;

                    $totalQuantity += CHtml::value($detail, 'quantity');
                endif;
            }

            $worksheet->getStyle("E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("D{$counter}", 'Total');
            $worksheet->setCellValue("E{$counter}", CHtml::encode(ceil($totalQuantity)));

            $counter++;
            $counter++;
        }
        
        for ($col = 'A'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }


        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Pengiriman.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
