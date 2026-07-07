<?php

class SaleChequeController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('saleChequeReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $saleCheque = Search::bind(new SaleChequeHeader('search'), isset($_GET['SaleChequeHeader']) ? $_GET['SaleChequeHeader'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);
        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';
        $customers = Customer::model()->findAllByAttributes(
                array(
            'branch_id' => $branchId
                ), array(
            'order' => 'name ASC'
                )
        );

        $saleChequeSummary = new SaleChequeSummary($saleCheque->search());

        $saleChequeSummary->setupLoading();
        $saleChequeSummary->setupPaging($pageSize, $currentPage);
        $saleChequeSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'customerId' => $customerId
        );
        $saleChequeSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleChequeSummary, $branch, $saleChequeSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));


        $this->render('summary', array(
            'saleCheque' => $saleCheque,
            'saleChequeSummary' => $saleChequeSummary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'branch' => $branch,
            'customerId' => $customerId,
            'customers' => $customers
        ));
    }

    public function actionAjaxHtmlCustomer() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $customerId = '';
            $branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';

            $customers = Customer::model()->findAllByAttributes(
                    array(
                'branch_id' => $branchId
                    ), array(
                'order' => 'name ASC'
                    )
            );

            $this->renderPartial('_customer', array(
                'customers' => $customers,
                'customerId' => $customerId
            ));
        }
    }

    protected function saveToExcel($saleChequeSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penerimaan Giro');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penerimaan Giro');

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
        $worksheet->setCellValue('A2', 'Laporan Penerimaan Giro');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:E5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Tanggal');
        $worksheet->setCellValue('B5', 'Jatuh Tempo');
        $worksheet->setCellValue('C5', 'Nomor Giro');
        $worksheet->setCellValue('D5', 'Customer');
        $worksheet->setCellValue('E5', 'Catatan');

        $worksheet->setCellValue('A6', 'Tanda Terima');
        $worksheet->setCellValue('B6', 'Total');
        $worksheet->setCellValue('C6', 'Bank');
        $worksheet->setCellValue('D6', 'Cheque Number');
        $worksheet->setCellValue('E6', 'Amount');

        $worksheet->getStyle('A6:E6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;

        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->receive_date))));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->due_date))));
            $worksheet->setCellValue("C{$counter}", CHtml::encode($header->getCodeNumber(SaleCheque::CN_CONSTANT)));
            $worksheet->setCellValue("D{$counter}", CHtml::encode($header->customer->company));
            $worksheet->setCellValue("E{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'note'))));

            $counter++;


            foreach ($header->saleChequeDetails as $detail) {
                $worksheet->getStyle("C{$counter}:D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode($detail->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($detail, 'saleReceiptHeader.totalInvoice')));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'bank')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'cheque_number')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'amount')));

                $counter++;
            }

            $worksheet->getStyle("B{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:E{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("A{$counter}", 'Total');
            $worksheet->setCellValue("B{$counter}", CHtml::encode($header->getTotalSaleReceipt()));
            $worksheet->setCellValue("E{$counter}", CHtml::encode($header->totalAmount));
            $counter++;
            $counter++;
        }



        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penerimaan Giro.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
