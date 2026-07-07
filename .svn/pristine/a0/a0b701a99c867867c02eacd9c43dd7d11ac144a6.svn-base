<?php

class PurchaseController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view'
                || $filterChain->action->id === 'create'
                || $filterChain->action->id === 'memo'
                || $filterChain->action->id === 'ajaxJsonShippingTotal'
                || $filterChain->action->id === 'ajaxHtmlAddProduct'
                || $filterChain->action->id === 'ajaxHtmlRemoveProduct'
                || $filterChain->action->id === 'ajaxJsonDiscountTotal'
                || $filterChain->action->id === 'ajaxJsonTotal'
                || $filterChain->action->id === '/completion/supplier'
                || $filterChain->action->id === 'ajaxJsonTaxTotal') {
            if (!(Yii::app()->user->checkAccess('purchaseCreate') || Yii::app()->user->checkAccess('purchaseEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('purchaseEdit')))
                $this->redirect(array('/site/login'));
        }


        $filterChain->run();
    }

    public function actionCreate() {
        $purchase = $this->instantiate(null);
        $purchase->header->admin_id = Yii::app()->user->id;

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $dataProvider = $product->search();
        $dataProvider->criteria->with = array(
            'category:resetScope',
            'type:resetScope',
            'brand:resetScope',
            'material:resetScope',
            'discMaterial:resetScope',
            'bodyType:resetScope',
            'connection:resetScope',
            'grade:resetScope',
            'classification:resetScope',
            'thickness:resetScope',
            'variety:resetScope',
            'connectionMaterial:resetScope',
            'parameter:resetScope',
            'range:resetScope',
            'handling:resetScope',
            'bellow:resetScope',
            'unit:resetScope',
        );

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchase);
            $purchase->generateCodeNumber($purchase->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchase->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchase->header->date)));

            if ($purchase->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchase->header->id));
            }
        }

        $this->render('create', array(
            'purchase' => $purchase,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $purchase = $this->instantiate($id);
        $purchase->header->admin_id = Yii::app()->user->id;

        $supplier = $purchase->header->supplier(array('scopes' => 'resetScope'));

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $dataProvider = $product->search();
        $dataProvider->criteria->with = array(
            'category:resetScope',
            'type:resetScope',
            'brand:resetScope',
            'material:resetScope',
            'discMaterial:resetScope',
            'bodyType:resetScope',
            'connection:resetScope',
            'grade:resetScope',
            'classification:resetScope',
            'thickness:resetScope',
            'variety:resetScope',
            'connectionMaterial:resetScope',
            'parameter:resetScope',
            'range:resetScope',
            'handling:resetScope',
            'bellow:resetScope',
            'unit:resetScope',
        );

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchase);
            
            if ($purchase->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $purchase->header->id));
        }

        $this->render('update', array(
            'purchase' => $purchase,
            'product' => $product,
            'supplier' => $supplier,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {
        $purchase = $this->loadModel($id);

        $supplier = $purchase->supplier(array('scopes' => 'resetScope'));
        $branch = $purchase->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('purchase_header_id', $purchase->id);
        $detailsDataProvider = new CActiveDataProvider('PurchaseDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'purchase' => $purchase,
            'supplier' => $supplier,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $purchaseHeader = $this->loadModel($id);
            if ($purchaseHeader !== null) {
                $purchaseHeader->is_inactive = ActiveRecord::INACTIVE;
                $purchaseHeader->update(array('is_inactive'));

                foreach ($purchaseHeader->purchaseDetails as $purchaseDetail) {
                    $purchaseDetail->is_inactive = ActiveRecord::INACTIVE;
                    $purchaseDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionMemo($id) {
//        if (!(Yii::app()->user->checkAccess('administrator'))) {
//            if (!(isset(Yii::app()->session['PurchaseMemoAllowed']) && Yii::app()->session['PurchaseMemoAllowed'] === true))
//                $this->redirect(array('admin'));
//        }
//
//        Yii::app()->session->remove('PurchaseMemoAllowed');

        $purchase = $this->loadModel($id);

        $this->memoToExcel($purchase);

    }

    public function actionAdmin() {
        $purchase = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $purchase->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'purchase' => $purchase,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAjaxHtmlAddProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchase = $this->instantiate($id);

            $this->loadState($purchase);

            if (isset($_POST['ProductId']))
                $purchase->addDetail($_POST['ProductId']);

            $this->renderPartial('_detail', array(
                'purchase' => $purchase,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchase = $this->instantiate($id);

            $this->loadState($purchase);

            $purchase->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'purchase' => $purchase,
            ));
        }
    }

    public function actionAjaxJsonTotal($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchase = $this->instantiate($id);

            $this->loadState($purchase);

            $unitPrice = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase->details[$index], 'unit_price')));
            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase->details[$index], 'total')));
            $subTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->subTotal));
            $taxPercentage = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->getTaxPercentage()));
            $taxValue = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->getCalculatedTax()));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->grandTotal));

            echo CJSON::encode(array(
                'unitPrice' => $unitPrice,
                'total' => $total,
                'subTotal' => $subTotal,
                'taxPercentage' => $taxPercentage,
                'taxValue' => $taxValue,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionAjaxJsonCodeNumberTaxTotal($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchase = $this->instantiate($id);
            $this->loadState($purchase);

            $purchase->generateCodeNumber($purchase->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchase->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchase->header->date)));
            $codeNumber = CHtml::encode($purchase->header->getCodeNumber(PurchaseHeader::CN_CONSTANT));
            $subTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->getSubTotal()));
            $taxPercentage = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->getTaxPercentage()));
            $taxValue = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->getCalculatedTax()));
            $grandTotal = Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase, 'grandTotal'));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
                'subTotal' => $subTotal,
                'taxPercentage' => $taxPercentage,
                'taxValue' => $taxValue,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    protected function memoToExcel($purchase) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Purchase');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);


        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Purchase');


        if ($purchase->branch_id != 4) {
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($worksheet);
            $objDrawingPType->setName("Logo");
            $objDrawingPType->setPath(Yii::app()->basePath . "/images/logo/logo" . $purchase->branch->id . ".jpg");
            $objDrawingPType->setCoordinates('A1');
            $objDrawingPType->setWidth(85);
            $objDrawingPType->setResizeProportional(true);
            $objDrawingPType->setOffsetX(0);
            $objDrawingPType->setOffsetY(3);
        }
        $worksheet->getRowDimension(1)->setRowHeight(1);

        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setAutoSize(false);

        $worksheet->getColumnDimension('B')->setWidth('1.35');
        $worksheet->getColumnDimension('E')->setWidth('1.35');
        $worksheet->getColumnDimension('H')->setWidth('8');
        $worksheet->getColumnDimension('A')->setWidth('7.65');

        $counter = 2;
        //branch
        if ($purchase->branch_id != 4) {

            $worksheet->getStyle("D{$counter}")->getFont()->setBold(true);
            $worksheet->getStyle("D{$counter}")->getFont()->setSize(14);
            $worksheet->setCellValue("D{$counter}", CHtml::value($purchase, 'branch.name'));
            $counter++;

            $counterAddress = $counter + 1;
            $worksheet->mergeCells("D{$counter}:G{$counter}");
            $worksheet->getStyle("D{$counter}")->getAlignment()->setWrapText(TRUE);
            $worksheet->setCellValue("D{$counter}", CHtml::encode($purchase->branch->address));

            $counter++;

            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("D{$counter}", 'Telp. ');
            $worksheet->setCellValue("E{$counter}", ': ');
            $worksheet->setCellValue("F{$counter}", CHtml::value($purchase, 'branch.phone'));
            $counter++;

            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("D{$counter}", 'NPWP ');
            $worksheet->setCellValue("E{$counter}", ': ');
            $worksheet->setCellValue("F{$counter}", CHtml::value($purchase, 'branch.npwp'));
            $counter++;
        }

        $counter = 3;
        $worksheet->setCellValue("I{$counter}", 'Tanggal');
        $worksheet->setCellValue("J{$counter}", ': ');
        $worksheet->mergeCells("K{$counter}:L{$counter}");
        $worksheet->setCellValue("K{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchase, 'date'))));

        $counter++;

        $worksheet->setCellValue("I{$counter}", 'PO Nomor');
        $worksheet->setCellValue("J{$counter}", ': ');
        $worksheet->mergeCells("K{$counter}:L{$counter}");
        $worksheet->setCellValue("K{$counter}", $purchase->getCodeNumber(PurchaseHeader::CN_CONSTANT));
        $counter++;

        $worksheet->getStyle("I{$counter}:L{$counter}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        foreach (range('I', 'L') as $columnID) {
            $worksheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
        }

        $worksheet->getColumnDimension('J')->setAutoSize(false);
        $worksheet->getColumnDimension('J')->setWidth('1.35');

        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('5');

        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('15');

        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('16');

        $worksheet->getColumnDimension('I')->setAutoSize(false);
        $worksheet->getColumnDimension('I')->setWidth('10');

        $worksheet->getColumnDimension('K')->setAutoSize(false);
        $worksheet->getColumnDimension('K')->setWidth('10');

        $worksheet->getColumnDimension('L')->setAutoSize(false);
        $worksheet->getColumnDimension('L')->setWidth('16.0');

        $worksheet->mergeCells("I{$counter}:L{$counter}");
        $worksheet->getStyle("I{$counter}")->getFont()->setSize(7);
        $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->setCellValue("I{$counter}", 'Nomor PO ini harap dicantumkan pada Surat Jalan & Invoice');
        $counter += 2;

        //title
        $worksheet->mergeCells("A{$counter}:L{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}")->getFont()->setSize(14);
        $worksheet->setCellValue("A{$counter}", 'ORDER PEMBELIAN');
        $counter++;

        //header
        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("A{$counter}", 'Kepada');
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.name'));
        $worksheet->setCellValue("I{$counter}", 'Dikirim ke');
        $worksheet->setCellValue("J{$counter}", ': ');
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Telp.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.phone'));
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Fax.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.fax'));
        $counter++;

        //data
        $worksheet->mergeCells("B{$counter}:G{$counter}");
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->setCellValue("A{$counter}", 'No.');
        $worksheet->setCellValue("B{$counter}", 'Nama Barang');
        $worksheet->setCellValue("H{$counter}", 'Qty');
        $worksheet->setCellValue("I{$counter}", 'Satuan');
        $worksheet->setCellValue("J{$counter}", 'Harga');
        $worksheet->setCellValue("L{$counter}", 'Total (IDR)');
        $counter++;

        foreach ($purchase->purchaseDetails as $i => $detail) {
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:K{$counter}");
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $worksheet->getStyle("H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("J{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("A{$counter}", $i + 1);
            $worksheet->setCellValue("B{$counter}", CHtml::value($detail, 'product.name') . ' ' . CHtml::value($detail, 'product.size'));
            $worksheet->setCellValue("H{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity')));
            $worksheet->setCellValue("I{$counter}", CHtml::value($detail, 'product.unit.name'));
            $worksheet->setCellValue("J{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price')));
            $worksheet->setCellValue("L{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total')));
            $counter++;
        }

        //empty rows ten times
        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--) {
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:K{$counter}");
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;
        }

        //data footer
        $counter2 = $counter;             //checkpoint for bold-ing many cell rows
        $worksheet->mergeCells("A{$counter}:G{$counter}");
        $worksheet->mergeCells("H{$counter}:K{$counter}");
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        if ($purchase->is_non_tax == 2) {
            $worksheet->setCellValue("A{$counter}", '* Harga Barang ' . $purchase->getTaxType($purchase->is_non_tax));
        }
        $worksheet->setCellValue("H{$counter}", 'Sub Total ');
        $worksheet->setCellValue("L{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase, 'subTotal')));
        $counter++;

        $worksheet->mergeCells("A{$counter}:G{$counter}");
        $worksheet->mergeCells("H{$counter}:K{$counter}");

        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("H{$counter}", 'PPN ');
        $worksheet->setCellValue("L{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase, 'calculatedTax')));
        $counter++;

        $worksheet->mergeCells("A{$counter}:G{$counter}");
        $worksheet->mergeCells("H{$counter}:K{$counter}");
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("H{$counter}", 'Grand Total ');
        $worksheet->setCellValue("L{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchase, 'grandTotal')));
        $counter++;

        $worksheet->mergeCells("A{$counter}:D{$counter}");
        $worksheet->mergeCells("G{$counter}:H{$counter}");
        $worksheet->mergeCells("J{$counter}:K{$counter}");

        $worksheet->setCellValue("A{$counter}", 'Dibuat oleh,');
        $worksheet->setCellValue("F{$counter}", 'Disetujui,');
        $worksheet->setCellValue("J{$counter}", 'Diterima,');

        $counter += 4;

        $worksheet->setCellValue("A{$counter}", 'Purchasing');
        $worksheet->setCellValue("F{$counter}", 'Direksi');
        $worksheet->setCellValue("J{$counter}", 'Supplier');

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="purchase.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionMemoShipping($id) {
//        if (!(Yii::app()->user->checkAccess('administrator'))) {
//            if (!(isset(Yii::app()->session['PurchaseMemoAllowed']) && Yii::app()->session['PurchaseMemoAllowed'] === true))
//                $this->redirect(array('admin'));
//        }
//
//        Yii::app()->session->remove('PurchaseMemoAllowed');

        $purchase = $this->loadModel($id);

        $this->memoShippingToExcel($purchase);

    }

    protected function memoShippingToExcel($purchase) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Purchase');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);


        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Purchase');

        if ($purchase->branch_id != 4) {
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($worksheet);
            $objDrawingPType->setName("Logo");
            $objDrawingPType->setPath(Yii::app()->basePath . "/images/logo/logo" . $purchase->branch->id . ".jpg");
            $objDrawingPType->setCoordinates('A1');
            $objDrawingPType->setWidth(85);
            $objDrawingPType->setResizeProportional(true);
            $objDrawingPType->setOffsetX(0);
            $objDrawingPType->setOffsetY(3);
        }
        $worksheet->getRowDimension(1)->setRowHeight(1);

        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setAutoSize(false);

        $worksheet->getColumnDimension('B')->setWidth('1.35');
        $worksheet->getColumnDimension('E')->setWidth('1.35');
        $worksheet->getColumnDimension('H')->setWidth('8');
        $worksheet->getColumnDimension('A')->setWidth('7.65');

        $counter = 2;
        //branch
        if ($purchase->branch_id != 4) {
            //$worksheet->setCellValue("A{$counter}",CHtml::image(Yii::app()->baseUrl.'/images/logo/logo'.$purchase->branch->id.'.jpg', "image", array("width"=>'100%')));

            $worksheet->getStyle("D{$counter}")->getFont()->setBold(true);
            $worksheet->getStyle("D{$counter}")->getFont()->setSize(14);
            $worksheet->setCellValue("D{$counter}", CHtml::value($purchase, 'branch.name'));
            $counter++;

            $counterAddress = $counter + 1;
            $worksheet->mergeCells("D{$counter}:G{$counter}");
            $worksheet->getStyle("D{$counter}")->getAlignment()->setWrapText(TRUE);
            $worksheet->setCellValue("D{$counter}", CHtml::encode($purchase->branch->address));

            $counter++;

            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("D{$counter}", 'Telp. ');
            $worksheet->setCellValue("E{$counter}", ': ');
            $worksheet->setCellValue("F{$counter}", CHtml::value($purchase, 'branch.phone'));
            $counter++;

            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("D{$counter}", 'NPWP ');
            $worksheet->setCellValue("E{$counter}", ': ');
            $worksheet->setCellValue("F{$counter}", CHtml::value($purchase, 'branch.npwp'));
            $counter++;
        }

        $counter = 3;
        $worksheet->setCellValue("I{$counter}", 'Tanggal');
        $worksheet->setCellValue("J{$counter}", ': ');
        $worksheet->mergeCells("K{$counter}:L{$counter}");
        $worksheet->setCellValue("K{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchase, 'date'))));

        $counter++;

        $worksheet->setCellValue("I{$counter}", 'No. PO');
        $worksheet->setCellValue("J{$counter}", ': ');
        $worksheet->mergeCells("K{$counter}:L{$counter}");
        $worksheet->setCellValue("K{$counter}", $purchase->getCodeNumber(PurchaseHeader::CN_CONSTANT));
        $counter++;

        foreach (range('I', 'L') as $columnID) {
            $worksheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $worksheet->getColumnDimension('J')->setAutoSize(false);
        $worksheet->getColumnDimension('J')->setWidth('1.35');

        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('5');

        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('14');

        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('14');

        $worksheet->getColumnDimension('I')->setAutoSize(false);
        $worksheet->getColumnDimension('I')->setWidth('10');

        $worksheet->getColumnDimension('K')->setAutoSize(false);
        $worksheet->getColumnDimension('K')->setWidth('10');

        $worksheet->getColumnDimension('L')->setAutoSize(false);
        $worksheet->getColumnDimension('L')->setWidth('16.0');

        $counter += 2;

        //title
        $worksheet->mergeCells("A{$counter}:L{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}")->getFont()->setSize(14);
        $worksheet->setCellValue("A{$counter}", 'MEMO');
        $counter++;

        //header
        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("A{$counter}", 'Kepada');
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.name'));
        $worksheet->setCellValue("I{$counter}", 'Dikirim ke');
        $worksheet->setCellValue("J{$counter}", ': ');
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Telp.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.phone'));
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Fax.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchase, 'supplier.fax'));
        $counter++;

        //data
        $worksheet->mergeCells("B{$counter}:G{$counter}");
		$worksheet->mergeCells("J{$counter}:L{$counter}");
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->setCellValue("A{$counter}", 'No.');
        $worksheet->setCellValue("B{$counter}", 'Nama Barang');
        $worksheet->setCellValue("H{$counter}", 'Qty');
        $worksheet->setCellValue("I{$counter}", 'Satuan');
		$worksheet->setCellValue("J{$counter}", 'Keterangan');
        $counter++;

        foreach ($purchase->purchaseDetails as $i => $detail) {
            $worksheet->mergeCells("B{$counter}:G{$counter}");
			$worksheet->mergeCells("J{$counter}:L{$counter}");
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("C{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $worksheet->getStyle("H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->setCellValue("A{$counter}", $i + 1);
            $worksheet->setCellValue("B{$counter}", CHtml::value($detail, 'product.name') . ' ' . CHtml::value($detail, 'product.size'));
            $worksheet->setCellValue("H{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity')));
            $worksheet->setCellValue("I{$counter}", CHtml::value($detail, 'product.unit.name'));
			$worksheet->setCellValue("J{$counter}", '');
            $counter++;
        }

        //empty rows ten times
        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--) {
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:L{$counter}");
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;
        }

		$worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$counter++;
        $worksheet->mergeCells("J{$counter}:L{$counter}");
        $worksheet->setCellValue("J{$counter}", 'Hormat Kami,');
        $counter += 5;

		$worksheet->mergeCells("J{$counter}:L{$counter}");
        $worksheet->setCellValue("J{$counter}", 'Purchasing');

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="purchase-shipping.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function instantiate($id) {
        if (empty($id))
            $purchase = new Purchase(new PurchaseHeader(), array());
        else {
            $purchaseHeader = $this->loadModel($id);
            $purchase = new Purchase($purchaseHeader, $purchaseHeader->purchaseDetails);
        }

        return $purchase;
    }

    public function loadModel($id) {
        $model = PurchaseHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadState($purchase) {
        if (isset($_POST['PurchaseHeader'])) {
            $purchase->header->attributes = $_POST['PurchaseHeader'];
        }
        if (isset($_POST['PurchaseDetail'])) {
            foreach ($_POST['PurchaseDetail'] as $i => $item) {
                if (isset($purchase->details[$i]))
                    $purchase->details[$i]->attributes = $item;
                else {
                    $detail = new PurchaseDetail();
                    $detail->attributes = $item;
                    $purchase->details[] = $detail;
                }
            }
            if (count($_POST['PurchaseDetail']) < count($purchase->details))
                array_splice($purchase->details, $i + 1);
        }
        else
            $purchase->details = array();
    }

}