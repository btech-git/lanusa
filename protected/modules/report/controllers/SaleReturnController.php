<?php

class SaleReturnController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary'
                || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('saleReturnReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

        $salesReturnHeader = Search::bind(new SaleReturnHeader('search'), isset($_GET['SaleReturnHeader']) ? $_GET['SaleReturnHeader'] : array());
        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $customerId = isset($_GET['CustomerId']) ? $_GET['CustomerId'] : '';
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branch = Branch::model()->findByPk($branchId);
        $customers = Customer::model()->findAllByAttributes(
                array(
            'branch_id' => $branchId
                ), array(
            'order' => 'name ASC'
                )
        );

        $saleReturnSummary = new SaleReturnSummary($salesReturnHeader->search());
        $saleReturnSummary->setupLoading();
        $saleReturnSummary->setupPaging($pageSize, $currentPage);
        $saleReturnSummary->setupSorting();

        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'customerId' => $customerId
        );
        $saleReturnSummary->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($saleReturnSummary, $branch, $saleReturnSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'saleReturnHeader' => $salesReturnHeader,
            'saleReturnSummary' => $saleReturnSummary,
            'branchId' => $branchId,
            'customerId' => $customerId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branch' => $branch,
            'customers' => $customers
        ));
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

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0.00;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

    protected function saveToExcel($saleReturnSummary, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Retur Penjualan Barang');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Retur Penjualan');

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


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Retur Penjualan Barang');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


        $worksheet->setCellValue('A5', 'Retur #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Invoice #');
        $worksheet->setCellValue('D5', 'Customer');
        $worksheet->setCellValue('E5', 'Gudang');
        $worksheet->setCellValue('F5', 'Catatan');

        $worksheet->setCellValue('A6', 'Nama Barang');
        $worksheet->setCellValue('B6', 'Ukuran');
        $worksheet->setCellValue('C6', 'Jumlah');
        $worksheet->setCellValue('D6', 'Satuan');
        $worksheet->setCellValue('E6', 'Harga Satuan');
        $worksheet->setCellValue('F6', 'Total');

        $worksheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $counter = 7;
        foreach ($dataProvider->data as $header) {
            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(SaleReturnHeader::CN_CONSTANT)));
            $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
            $worksheet->setCellValue("C{$counter}", CHtml::encode($header->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)));
            $worksheet->setCellValue("D{$counter}", CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'saleInvoice.deliveryHeader.saleHeader.customer.name' : 'saleInvoice.deliveryHeader.saleHeader.customer.company')));
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'warehouse.name')));
            $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'note')));
            $counter++;


            foreach ($header->saleReturnDetails as $detail) {
                $worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->getStyle("E{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'product.name')));
                $worksheet->setCellValue("B{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($detail, 'product.size'))));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($detail, 'quantity')));
                $worksheet->setCellValue("D{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($detail, 'product.unit.name'))));
                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'unitPrice')));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($detail, 'total')));
                $counter++;
            }

            $worksheet->getStyle("F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("E{$counter}", 'Total');
            $worksheet->setCellValue("F{$counter}", CHtml::encode(floor(CHtml::value($header, 'subTotal'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("E{$counter}", 'Tax' . CHtml::encode(floor(CHtml::value($header, 'tax'))) . '%');
            $worksheet->setCellValue("F{$counter}", CHtml::encode(floor(CHtml::value($header, 'calculatedTax'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("E{$counter}", 'Ongkos Kirim');
            $worksheet->setCellValue("F{$counter}", CHtml::encode(floor(CHtml::value($header, 'shipping_fee'))));
            $counter++;

            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("E{$counter}", 'Grand Total');
            $worksheet->setCellValue("F{$counter}", CHtml::encode(floor(CHtml::value($header, 'grandTotal'))));
            $counter++;
            $counter++;
        }
        
        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("E{$counter}", 'Total Retur');
        $worksheet->setCellValue("F{$counter}", CHtml::encode(($this->reportGrandTotal($saleReturnSummary->dataProvider))));
        $counter++;
        $counter++;


        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Retur Penjualan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

}
