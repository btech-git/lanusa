<?php

class AdjustmentController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('stockAdjustmentReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $adjustmentHeader = Search::bind(new AdjustmentHeader('search'), isset($_GET['AdjustmentHeader']) ? $_GET['AdjustmentHeader'] : array());
        //$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $adjustmentSummary = new AdjustmentSummary($adjustmentHeader->search());
        $adjustmentSummary->setupLoading();
        $adjustmentSummary->setupPaging($pageSize, $currentPage);
        $adjustmentSummary->setupSorting();
        $adjustmentSummary->setupFilter($startDate, $endDate);
        $adjustmentSummary->setupBranch($branch);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($adjustmentSummary, $branch, $adjustmentSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'adjustmentHeader' => $adjustmentHeader,
            'adjustmentSummary' => $adjustmentSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'listData' => $listData
        ));
    }

    protected function saveToExcel($adjustmentSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penyesuaian Stok');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penyesuaian Stok');

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
        $worksheet->setCellValue('A2', 'Laporan Penyesuaian Stock Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:G5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Penyesuaian #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->mergeCells('C5:D5');
        $worksheet->setCellValue('C5', 'Gudang');
        $worksheet->mergeCells('E5:G5');
        $worksheet->setCellValue('F5', 'Catatan');

        $worksheet->mergeCells('A6:B6');
        $worksheet->setCellValue('A6', 'Nama Barang');
        $worksheet->setCellValue('C6', 'Ukuran');
        $worksheet->setCellValue('D6', 'Stok');
        $worksheet->setCellValue('E6', 'Penyesuaian');
        $worksheet->setCellValue('F6', 'Perbedaan');
        $worksheet->setCellValue('G6', 'Satuan');

        $worksheet->getStyle('A6:G6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(AdjustmentHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->mergeCells("C{$counter}:D{$counter}");
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, 'warehouse.name')));
            $worksheet->mergeCells("E{$counter}:G{$counter}");
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'note')));

            $counter++;

            foreach ($header->adjustmentDetails as $detail) {

                $worksheet->getStyle("A{$counter}:C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $worksheet->getStyle("D{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->mergeCells("A{$counter}:B{$counter}");
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'product.name')));
                $worksheet->setCellValue("C{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($detail, 'product.size'))));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'quantity_current')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'quantity_adjustment')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'quantity_adjustment')));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'product.unit.name')));
                $counter++;
            }

            $counter++;
        }


        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penyesuaian Barang.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
