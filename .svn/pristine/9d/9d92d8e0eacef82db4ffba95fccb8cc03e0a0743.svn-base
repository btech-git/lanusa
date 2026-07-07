<?php

class SaleController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('saleReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $saleHeader = Search::bind(new SaleHeader('search'), isset($_GET['SaleHeader']) ? $_GET['SaleHeader'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
        $reference = isset($_GET['reference']) ? $_GET['reference'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);
        $customerId = isset($_GET['CustomerId']) ? $_GET['CustomerId'] : '';
        $customers = Customer::model()->findAllByAttributes(
                array(
            'branch_id' => $branchId
                ), array(
            'order' => 'name ASC'
                )
        );

        $saleSummary = new SaleSummary($saleHeader->search());
        $saleSummary->setupLoading();
        $saleSummary->setupPaging($pageSize, $currentPage);
        $saleSummary->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'reference' => $reference,
            'customerId' => $customerId
        );
        $saleSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleSummary, $branch, $saleSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'saleHeader' => $saleHeader,
            'saleSummary' => $saleSummary,
            'customerId' => $customerId,
            'branchId' => $branchId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branch' => $branch,
            'customers' => $customers
        ));
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

    public function actionAjaxHtmlCustomer() {         //find customer based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $customerId = '';
            $branchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : '';

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

    protected function saveToExcel($saleSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penjualan Barang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penjualan');

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

        $worksheet->mergeCells('A1:I1');
        $worksheet->mergeCells('A2:I2');
        $worksheet->mergeCells('A3:I3');

        $worksheet->getStyle('A1:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:I6')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Penjualan Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));



        $worksheet->getStyle('A5:I5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Penjualan #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Customer');
        $worksheet->setCellValue('D5', 'PO #');
        $worksheet->setCellValue('E5', 'Catatan');

        $worksheet->mergeCells('A6:C6');
        $worksheet->setCellValue('A6', 'Nama Barang');
        $worksheet->setCellValue('D6', 'Ukuran');
        $worksheet->setCellValue('E6', 'Jumlah');
        $worksheet->setCellValue('F6', 'Satuan');
        $worksheet->setCellValue('G6', 'Harga Satuan');
        $worksheet->setCellValue('H6', 'Diskon');
        $worksheet->setCellValue('I6', 'Total');

        $worksheet->getStyle('A6:I6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(SaleHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, isset($header->customer->company) ? 'customer.company' : 'customer.name')));
            $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($header, 'reference')));
            $worksheet->mergeCells("E{$counter}:I{$counter}");
            $worksheet->getStyle("E{$counter}:I{$counter}")->getAlignment()->setWrapText(true);
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'note')));
            $counter++;

            foreach ($header->saleDetails as $detail) {
                $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("G{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//				$worksheet->getStyle("E{$counter}")->getNumberFormat()->setFormatCode('#,##0');
//                                $worksheet->getStyle("G{$counter}:I{$counter}")->getNumberFormat()->setFormatCode('#,##0');
                $worksheet->mergeCells("A{$counter}:C{$counter}");
                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'product_name')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($detail, 'product.size')));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(ceil(CHtml::value($detail, 'quantity'))));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'product.unit.name')));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($detail, 'unit_price')));
                $worksheet->setCellValue("H{$counter}", CHtml::encode(CHtml::value($detail, 'discount')));
                $worksheet->setCellValue("I{$counter}", CHtml::encode(CHtml::value($detail, 'total')));
                $counter++;
            }

            $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("G{$counter}", 'Sub Total');
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("I{$counter}", CHtml::encode(($header->subTotal)));
            $counter++;
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("G{$counter}", 'Disc');
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("I{$counter}", CHtml::encode(($header->discount)));
            $counter++;
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("G{$counter}", 'Ongkos Kirim');
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("I{$counter}", CHtml::encode(($header->shipping_fee)));
            $counter++;
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("G{$counter}", 'PPN' . CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ($header->tax))) . '%');
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("I{$counter}", CHtml::encode(($header->calculatedTax)));
            $counter++;
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("G{$counter}", 'Grand Total');
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("I{$counter}", CHtml::encode(($header->grandTotal)));
            $counter++;
            $counter++;
        }

        $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->mergeCells("G{$counter}:H{$counter}");
        $worksheet->getStyle("G{$counter}:I{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("G{$counter}", 'TOTAL PENJUALAN');
        $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("I{$counter}", CHtml::encode($this->reportGrandTotal($saleSummary->dataProvider)));
        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penjualan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
