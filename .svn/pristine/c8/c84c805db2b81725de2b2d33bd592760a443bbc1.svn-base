<?php

class JournalVoucherController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary') {
            if (!(Yii::app()->user->checkAccess('adjustmentJournalReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $journal = Search::bind(new JournalVoucherHeader('search'), isset($_GET['JournalVoucherHeader']) ? $_GET['JournalVoucherHeader'] : array());
        //$branch = Branch::model()->findByPk(Yii::app()->user->branch_id);
        $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
        $listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $journalVoucherSummary = new JournalVoucherSummary($journal->search());
        $journalVoucherSummary->setupLoading();
        $journalVoucherSummary->setupPaging($pageSize, $currentPage);
        $journalVoucherSummary->setupSorting();
        $journalVoucherSummary->setupFilter($startDate, $endDate);
        $journalVoucherSummary->setupBranch($branch);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($journalVoucherSummary, $branch, $journalVoucherSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'journal' => $journal,
            'journalVoucherSummary' => $journalVoucherSummary,
            'branch' => $branch,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'listData' => $listData
        ));
    }

    protected function saveToExcel($journalVoucherSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Jurnal Voucher');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Jurnal Voucher');

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);


        $worksheet->mergeCells('A1:E1');
        $worksheet->mergeCells('A2:E2');
        $worksheet->mergeCells('A3:E3');

        $worksheet->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:E6')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Jurnal Voucher');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:E5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Voucher #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->mergeCells('C5:E5');
        $worksheet->setCellValue('C5', 'Catatan');


        $worksheet->setCellValue('A6', 'Kode Akun');
        $worksheet->setCellValue('B6', 'Nama Akun');
        $worksheet->setCellValue('C6', 'Debit');
        $worksheet->setCellValue('D6', 'Kredit');
        $worksheet->setCellValue('E6', 'Memo');

        $worksheet->getStyle('A6:E6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;

        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(JournalVoucherHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->setCellValue("C{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));
            $counter++;


            foreach ($header->journalVoucherDetails as $detail) {
                $worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("C{$counter}:D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'account_id')));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($detail, 'account.name')));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'debit')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'credit')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'memo')));
                $counter++;
            }

            $worksheet->getStyle("C{$counter}:D{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:E{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("B{$counter}", 'Total');
            $worksheet->setCellValue("C{$counter}", CHtml::encode(ceil($header->totalDebit)));
            $worksheet->setCellValue("D{$counter}", CHtml::encode(ceil($header->totalCredit)));
            $counter++;
            $counter++;
        }



        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Jurnal Voucher.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
