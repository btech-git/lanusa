<?php class ReceiveOutstandingReceiptController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'ajaxHtmlCustomer') {
            if (!(Yii::app()->user->checkAccess('receiveReport') ))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $receiveHeader = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
        $currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
        $currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';

        $branchId = isset($_GET['BranchId']) ? $_GET['BranchId'] : '';
        $branch = Branch::model()->findByPk($branchId);
        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';
        $suppliers = Supplier::model()->findAllByAttributes(
            array(
                'branch_id' => $branchId
            ), array(
                'order' => 'name ASC'
            )
        );

        $receiveOutstandingReceipt = new ReceiveOutstandingReceipt($receiveHeader->searchByPurchaseReceipt());
        $receiveOutstandingReceipt->setupLoading();
        $receiveOutstandingReceipt->setupPaging($pageSize, $currentPage);
        $receiveOutstandingReceipt->setupSorting();
        $filters = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchId' => $branchId,
            'supplierId' => $supplierId
        );
        $receiveOutstandingReceipt->setupFilter($filters);

        if (isset($_GET['SaveExcel']))
            $this->saveToExcel($receiveOutstandingReceipt, $branch, $receiveOutstandingReceipt->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));

        $this->render('summary', array(
            'receiveHeader' => $receiveHeader,
            'receiveOutstandingReceipt' => $receiveOutstandingReceipt,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentSort' => $currentSort,
            'branchId' => $branchId,
            'branch' => $branch,
            'supplierId' => $supplierId,
            'suppliers' => $suppliers
        ));
    }

    public function actionAjaxHtmlSupplier() {         //find supplier based on selected branch
        if (Yii::app()->request->isAjaxRequest) {
            $supplierId = '';
            $branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';

            $suppliers = Supplier::model()->findAllByAttributes(
                array(
                    'branch_id' => $branchId
                ), array(
                    'order' => 'name ASC'
                )
            );

            $this->renderPartial('_supplier', array(
                'suppliers' => $suppliers,
                'supplierId' => $supplierId
            ));
        }
    }
    
    protected function saveToExcel($receiveOutstandingReceipt, $branch, $dataProvider, array $options = array()) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Penerimaan Belum TT');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penerimaan Belum TT');

        $worksheet->mergeCells('A1:M1');
        $worksheet->mergeCells('A2:M2');
        $worksheet->mergeCells('A3:M3');

        $worksheet->getStyle('A1:M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:M5')->getFont()->setBold(true);


        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branch, 'name')));
        $worksheet->setCellValue('A2', 'Laporan Penerimaan Belum TT');
        $worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

        $worksheet->getStyle('A5:M5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle('A5:M5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->setCellValue('A5', 'Penerimaan #');
        $worksheet->setCellValue('B5', 'Tanggal');
        $worksheet->setCellValue('C5', 'Supplier');
        $worksheet->setCellValue('D5', 'PO #');
        $worksheet->setCellValue('E5', 'Tanggal PO');
        $worksheet->setCellValue('F5', 'Cabang');
        $worksheet->setCellValue('G5', 'Catatan');
        $worksheet->setCellValue('H5', 'Nama Barang');
        $worksheet->setCellValue('I5', 'Ukuran');
        $worksheet->setCellValue('J5', 'Quantity');
        $worksheet->setCellValue('K5', 'Satuan');
        $worksheet->setCellValue('L5', 'Harga Satuan');
        $worksheet->setCellValue('M5', 'Total');

        $counter = 7;
        $grandTotalQuantity = 0;
        $grandTotalPrice = 0.00;
        
        foreach ($dataProvider->data as $header) {
            foreach ($header->receiveDetails as $detail) {
                $totalPrice = $detail->totalReporting;
                
                $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(ReceiveHeader::CN_CONSTANT)));
                $worksheet->setCellValue("B{$counter}", CHtml::encode($header->date));
                $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($header, 'purchaseHeader.supplier.company')));
                $worksheet->setCellValue("D{$counter}", CHtml::encode($header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)));
                $worksheet->setCellValue("E{$counter}", CHtml::encode($header->purchaseHeader->date));
                $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($header, 'branch.name')));
                $worksheet->setCellValue("G{$counter}", CHtml::encode(CHtml::value($header, 'note')));
                $worksheet->setCellValue("H{$counter}", CHtml::encode(CHtml::value($detail, 'product.name')));
                $worksheet->setCellValue("I{$counter}", CHtml::value($detail, 'product.size'));
                $worksheet->setCellValue("J{$counter}", CHtml::encode(CHtml::value($detail, 'quantity')));
                $worksheet->setCellValue("K{$counter}", CHtml::encode(CHtml::value($detail, 'product.unit.name')));
                $worksheet->setCellValue("L{$counter}", CHtml::encode(CHtml::value($detail, 'unitPrice')));
                $worksheet->setCellValue("M{$counter}", CHtml::encode($totalPrice));

                $counter++;
            
                $grandTotalPrice += $totalPrice;
            }
            
            $grandTotalQuantity += $header->totalQuantity;
        }

        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $worksheet->getStyle("A{$counter}:M{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A{$counter}:M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->getStyle("A{$counter}:M{$counter}")->getFont()->setBold(true);
        
        $worksheet->setCellValue("A{$counter}", 'GRAND TOTAL');
        $worksheet->setCellValue("J{$counter}", CHtml::encode($grandTotalQuantity));
        $worksheet->setCellValue("M{$counter}", CHtml::encode($grandTotalPrice));
        $counter++;

		for($col = 'A'; $col !== 'M'; $col++) {
			$objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
		}	
		
        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Penerimaan Belum TT.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}