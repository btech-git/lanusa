<?php

class SaleController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create' || $filterChain->action->id === '/completion/customer' || $filterChain->action->id === 'ajaxHtmlAddProduct' || $filterChain->action->id === 'ajaxJsonDiscountTaxTotal' || $filterChain->action->id === 'ajaxJsonDownpaymentTaxTotal' || $filterChain->action->id === 'ajaxJsonGrandTotal' || $filterChain->action->id === 'ajaxHtmlRemoveProduct' || $filterChain->action->id === 'ajaxJsonTaxTotal' || $filterChain->action->id === 'ajaxJsonTotal' || $filterChain->action->id === 'ajaxHtmlUpdateAllProduct' || $filterChain->action->id === 'memo' || $filterChain->action->id === 'view') {
            if (!(Yii::app()->user->checkAccess('saleCreate') || Yii::app()->user->checkAccess('saleEdit'))) {
                $this->redirect(array('/site/login'));
            }
        }
        
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('saleEdit'))) {
                $this->redirect(array('/site/login'));
            }
        }
        
        if ($filterChain->action->id === 'memoPicking' || $filterChain->action->id === 'adminWarehouse' || $filterChain->action->id === 'viewWarehouse') {
            if (!(Yii::app()->user->checkAccess('pickingPrint'))) {
                $this->redirect(array('/site/login')); 
            }
        }
        $filterChain->run();
    }

    public function actionCreate() {
        $sale = $this->instantiate(null);

        $sale->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($sale);
            $sale->generateCodeNumber($sale->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($sale->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($sale->header->date)));

            if ($sale->save(Yii::app()->db)) {
                Yii::app()->session['SaleMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $sale->header->id));
            }
        }

        $this->render('create', array(
            'sale' => $sale,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $sale = $this->instantiate($id);

        $sale->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($sale);
            if ($sale->save(Yii::app()->db)) {
                $this->redirect(array('view', 'id' => $sale->header->id));
            }
        }

        $this->render('update', array(
            'sale' => $sale,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {
        $sale = $this->loadModel($id);

        $customer = $sale->customer(array('scopes' => 'resetScope'));
        $branch = $sale->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_header_id', $sale->id);
        $detailsDataProvider = new CActiveDataProvider('SaleDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'sale' => $sale,
            'customer' => $customer,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionViewWarehouse($id) {
        $sale = $this->loadModel($id);

        $customer = $sale->customer(array('scopes' => 'resetScope'));
        $branch = $sale->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_header_id', $sale->id);
        $detailsDataProvider = new CActiveDataProvider('SaleDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('viewWarehouse', array(
            'sale' => $sale,
            'customer' => $customer,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $saleHeader = $this->loadModel($id);
            if ($saleHeader !== null) {
                $saleHeader->is_inactive = ActiveRecord::INACTIVE;
                $saleHeader->update(array('is_inactive'));

                foreach ($saleHeader->saleDetails as $saleDetail) {
                    $saleDetail->is_inactive = ActiveRecord::INACTIVE;
                    $saleDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['SaleMemoAllowed']) && Yii::app()->session['SaleMemoAllowed'] === true)){
                $this->redirect(array('admin'));
            }
        }

        Yii::app()->session->remove('SaleMemoAllowed');

        $sale = $this->loadModel($id);
        $this->memoToExcel($sale);

//        $customer = $sale->customer(array('scopes' => 'resetScope'));
//        $branch = $sale->branch(array('scopes' => 'resetScope'));
//        $admin = $sale->admin(array('scopes' => 'resetScope'));

//        $saleDetails = $sale->saleDetails(array(
//            'with' => array(
//                'product:resetScope' => array(
//                    'with' => 'unit:resetScope',
//                ),
//            ),
//        ));

//        $saleHeaderText = ($sale->is_non_tax) ? '' : 'PT. LANUSA';
//        $saleCustomer = ($sale->is_non_tax) ? $sale->customer->name : $sale->customer->company;

//        $this->render('memo', array(
//            'sale' => $sale,
//            'branch' => $branch,
//            'admin' => $admin,
//            'customer' => $customer,
//            'saleDetails' => $saleDetails,
//            'deliveryHeaderText' => $saleHeaderText,
//            'deliveryCustomer' => $saleCustomer,
//        ));
    }

    protected function memoToExcel($sale) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Proforma Invoice');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Proforma Invoice');

        $worksheet->getColumnDimension('A')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setWidth('3');

        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setWidth('2');

        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('10');

        $worksheet->getColumnDimension('D')->setAutoSize(false);
        $worksheet->getColumnDimension('D')->setWidth('7');

        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setWidth('2');

        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('10');

        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('10');

        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('H')->setWidth('7');

        $worksheet->getColumnDimension('I')->setAutoSize(false);
        $worksheet->getColumnDimension('I')->setWidth('8');

        $worksheet->getColumnDimension('J')->setAutoSize(false);
        $worksheet->getColumnDimension('J')->setWidth('2');

        $worksheet->getColumnDimension('K')->setAutoSize(false);
        $worksheet->getColumnDimension('K')->setWidth('13');

        $worksheet->getColumnDimension('L')->setAutoSize(false);
        $worksheet->getColumnDimension('L')->setWidth('2');

        $worksheet->getColumnDimension('M')->setAutoSize(false);
        $worksheet->getColumnDimension('M')->setWidth('22');

        $counter = 2;
        //add image
        $image = Yii::app()->basePath . "/images/logo/logo" . $sale->branch->id . ".jpg";
        if (file_exists($image)) {

            $worksheet->mergeCells('A1:A4');
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Logo");
            $objDrawingPType->setPath($image);
            $objDrawingPType->setCoordinates('A1');
            $objDrawingPType->setWidth(85);
            $objDrawingPType->setResizeProportional(true);
            $objDrawingPType->setOffsetX(0);
            $objDrawingPType->setOffsetY(3);
        }
        
        $styleArray = array(
            'font' => array(
                'underline' => 'single',
                'size' => 16,
                'name' => 'Arial'
            )
        );

        $worksheet->mergeCells("D{$counter}:I{$counter}");
        $worksheet->getRowDimension('2')->setRowHeight(-1);
        $worksheet->getRowDimension('4')->setRowHeight(-1);
        $worksheet->getStyle("D{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("D{$counter}")->getFont()->setSize(18);
        $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $worksheet->getStyle("D{$counter}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $worksheet->getStyle("D{$counter}")->getFont()->setName('Bodoni MT Poster Compressed');

        $worksheet->setCellValue("D{$counter}", $sale->branch->name);
        $counter ++;
        $counterEnd = $counter + 1;
        $worksheet->getStyle("D{$counter}:I{$counterEnd}")->getAlignment()->setWrapText(true);
        $worksheet->mergeCells("D{$counter}:I{$counterEnd}");
        $worksheet->setCellValue("D{$counter}", strip_tags(nl2br($sale->branch->address)));
        $counter ++;

        $worksheet->setCellValue("D{$counter}", 'Telp');
        $worksheet->setCellValue("E{$counter}", ':');
        $worksheet->getStyle("F{$counter}")->getFont()->setSize(8);
        $worksheet->mergeCells("F{$counter}:H{$counter}");
        $worksheet->setCellValue("F{$counter}", $sale->branch->phone);
        
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'Tgl Faktur');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->setCellValue("M{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($sale->date)));

        $counter++;
        if ((int) $sale->branch_id !== 4) {
            $worksheet->setCellValue("D{$counter}", 'NPWP');
            $worksheet->setCellValue("E{$counter}", ':');
            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("F{$counter}", $sale->branch->npwp);
        }
        
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'No Faktur');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("M{$counter}", $sale->getCodeNumber(SaleHeader::CN_CONSTANT));

        $counter++;
        $worksheet->setCellValue("A{$counter}", 'Kepada');

        $counter++;
        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $worksheet->setCellValue("A{$counter}", $sale->customer->company);

        $worksheet->mergeCells("J{$counter}:K{$counter}");
        if ($sale->branch_id != 4) {
            $worksheet->setCellValue("J{$counter}", 'No Faktur Pajak');
            $worksheet->setCellValue("L{$counter}", ':');
//            $worksheet->setCellValue("M{$counter}", $sale->reference);
        }
        
        $counter++;
        $counter_3 = $counter + 1;
        $worksheet->mergeCells("A{$counter}:I{$counter_3}");
        $worksheet->getStyle("A{$counter}:B{$counter_3}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $worksheet->getStyle("A{$counter}:B{$counter_3}")->getAlignment()->setWrapText(true);
        $worksheet->setCellValue("A{$counter}", strip_tags(nl2br($sale->customer->address)));

        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'No PO');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->setCellValue("M{$counter}", CHtml::value($sale, 'reference'));

        $counter++; $counter++;
        
        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'PROFORMA INVOICE');
        $objPHPExcel->getActiveSheet()->getStyle("A{$counter}")->applyFromArray($styleArray);

        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'NPWP');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->setCellValue("M{$counter}", $sale->customer->npwp);

        $counter++;
        $worksheet->getStyle("A{$counter}:M{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:M{$counter}")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->setCellValue("A{$counter}", 'No.');
        $worksheet->mergeCells("B{$counter}:G{$counter}");
        $worksheet->setCellValue("B{$counter}", 'Nama Barang');
        $worksheet->setCellValue("H{$counter}", 'Qty');
        $worksheet->setCellValue("I{$counter}", 'Satuan');
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'Harga');
        $worksheet->mergeCells("L{$counter}:M{$counter}");
        $worksheet->setCellValue("L{$counter}", 'Total (IDR)');

        $counter++;
        $pageSize = 6;
        $emptyCells = 0;
        $itemNumber = 1;
        foreach ($sale->saleDetails as $detail) {
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("J{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("M{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("J{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:K{$counter}");
            $worksheet->mergeCells("L{$counter}:M{$counter}");

            $worksheet->setCellValue("A{$counter}", $itemNumber);
            $worksheet->setCellValue("B{$counter}", $detail->product_name);
            $worksheet->setCellValue("H{$counter}", $detail->quantity);
            $worksheet->setCellValue("I{$counter}", $detail->product->unit->name);
            $worksheet->setCellValue("J{$counter}", Yii::app()->numberFormatter->format('#,##0', $detail->unit_price));
            $worksheet->setCellValue("L{$counter}", Yii::app()->numberFormatter->format('#,##0', $detail->total));

            $counter++;
            $emptyCells++;
            $itemNumber++;
        }

        /* empty cells */
        for ($i = 0; $i < $pageSize - $emptyCells; $i++) {
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:K{$counter}");
            $worksheet->mergeCells("L{$counter}:M{$counter}");

            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("J{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("L{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("M{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $counter++;
        }

        $worksheet->mergeCells("H{$counter}:I{$counter}");
        $worksheet->setCellValue("H{$counter}", 'Hormat Kami,');
        
        $worksheet->getStyle("A{$counter}:M{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->setCellValue("J{$counter}", 'Sub Total');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->subTotal));

        $counter++;
        if ($sale->branch_id != 4) {
            $worksheet->setCellValue("A{$counter}", 'Keterangan:');
        }
        $worksheet->setCellValue("J{$counter}", 'DPP lain-lain');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->costOfGoodsSold));

        $counter++;
        
        if ($sale->branch_id != 4) {
            $worksheet->setCellValue("A{$counter}", 'Pembayaran a/n ' . $sale->branch->name);
        }
        $worksheet->setCellValue("J{$counter}", 'Disc');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->discount));

        $counter++;
        
        $worksheet->setCellValue("A{$counter}", $sale->branch->bank_account);
        if ($sale->branch_id != 4) {
            $worksheet->setCellValue("J{$counter}", 'PPN');
            $worksheet->setCellValue("L{$counter}", ':');
            $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->calculatedTax));
        } else {
            $worksheet->setCellValue("J{$counter}", 'Ongkos Kirim');
            $worksheet->setCellValue("L{$counter}", ':');
            $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->shipping_fee));
        }

        $counter++;
        
        $worksheet->setCellValue("J{$counter}", 'Grand Total');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $sale->grandTotal));

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment;filename="proforma_invoice.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionMemoPicking($id) {
        
        $sale = $this->loadModel($id);
        $this->memoPickingToExcel($sale);

    }

    protected function memoPickingToExcel($sale) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Persiapan');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Persiapan');

        if ($sale->branch_id != 4) {
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($worksheet);
            $objDrawingPType->setName("Logo");
            $objDrawingPType->setPath(Yii::app()->basePath . "/images/logo/logo" . $sale->branch->id . ".jpg");
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

        //branch
        if ($sale->branch_id != 4) {

            $worksheet->getStyle("D2")->getFont()->setBold(true);
            $worksheet->getStyle("D2")->getFont()->setSize(14);
            $worksheet->setCellValue("D2", CHtml::value($sale, 'branch.name'));

            $worksheet->mergeCells("D3:G3");
            $worksheet->getStyle("D3")->getAlignment()->setWrapText(TRUE);
            $worksheet->setCellValue("D3", CHtml::encode($sale->branch->address));

            $worksheet->mergeCells("F4:H4");
            $worksheet->setCellValue("D4", 'Telp. ');
            $worksheet->setCellValue("E4", ': ');
            $worksheet->setCellValue("F4", CHtml::value($sale, 'branch.phone'));

            $worksheet->mergeCells("F5:H5");
            $worksheet->setCellValue("D5", 'NPWP ');
            $worksheet->setCellValue("E5", ': ');
            $worksheet->setCellValue("F5", CHtml::value($sale, 'branch.npwp'));
        }

        $worksheet->setCellValue("I3", 'Tanggal');
        $worksheet->setCellValue("J3", ': ');
        $worksheet->mergeCells("K3:L3");
        $worksheet->setCellValue("K3", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($sale, 'date'))));

        $worksheet->setCellValue("I4", 'No. PO');
        $worksheet->setCellValue("J4", ': ');
        $worksheet->mergeCells("K4:L4");
        $worksheet->setCellValue("K4", $sale->getCodeNumber(SaleHeader::CN_CONSTANT));

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

        $counter = 7;

        //title
        $worksheet->mergeCells("A{$counter}:L{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}")->getFont()->setSize(14);
        $worksheet->setCellValue("A{$counter}", 'PERSIAPAN BARANG');
        $counter++;

        //header
        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("A{$counter}", 'Kepada');
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($sale, 'customer.name'));
        $worksheet->setCellValue("I{$counter}", 'Dikirim ke');
        $worksheet->setCellValue("J{$counter}", ': ');
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Telp.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($sale, 'customer.phone'));
        $counter++;

        $worksheet->mergeCells("C{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Fax.');
        $worksheet->getStyle("C{$counter}")->getAlignment()->setWrapText(TRUE);
        $worksheet->setCellValue("B{$counter}", ': ');
        $worksheet->setCellValue("C{$counter}", CHtml::value($sale, 'customer.fax'));
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

        foreach ($sale->saleDetails as $i => $detail) {
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
        $worksheet->setCellValue("J{$counter}", 'Gudang');

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="persiapan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionAdmin() {
        $sale = Search::bind(new SaleHeader('search'), isset($_GET['SaleHeader']) ? $_GET['SaleHeader'] : array());

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $customerCompany = (isset($_GET['SaleHeader']['customerCompany'])) ? $_GET['SaleHeader']['customerCompany'] : '';
        $sale->customerCompany = $customerCompany;
 
        $dataProvider = $sale->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'customer:resetScope',
        );

        if ($customerCompany) {
            $dataProvider->criteria->compare('customer.company', $customerCompany, TRUE);
        }

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'sale' => $sale,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdminWarehouse() {
        $sale = Search::bind(new SaleHeader('search'), isset($_GET['SaleHeader']) ? $_GET['SaleHeader'] : array());

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $customerCompany = (isset($_GET['SaleHeader']['customerCompany'])) ? $_GET['SaleHeader']['customerCompany'] : '';
        $sale->customerCompany = $customerCompany;
 
        $dataProvider = $sale->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'customer:resetScope',
        );

        if ($customerCompany) {
            $dataProvider->criteria->compare('customer.company', $customerCompany, TRUE);
        }

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('adminWarehouse', array(
            'sale' => $sale,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAjaxHtmlAddProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $sale = $this->instantiate($id);

            $this->loadState($sale);

            if (isset($_POST['ProductId'])) {
                $sale->addDetail($_POST['ProductId']);
            }

            $this->renderPartial('_detail', array(
                'sale' => $sale,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $sale = $this->instantiate($id);

            $this->loadState($sale);

            $sale->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'sale' => $sale,
            ));
        }
    }

    public function actionAjaxHtmlUpdateAllProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $sale = $this->instantiate($id);

            $this->loadState($sale);

            $this->renderPartial('_detail', array(
                'sale' => $sale,
            ));
        }
    }

    public function actionAjaxJsonTotal($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $sale = $this->instantiate($id);

            $this->loadState($sale);

            $unitPrice = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($sale->details[$index], 'unit_price')));
            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($sale->details[$index], 'total')));
            $subTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->subTotal));
            $taxPercentage = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->getTaxPercentage()));
            $taxValue = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->getCalculatedTax()));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->getGrandTotal()));

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
            $sale = $this->instantiate($id);
            $this->loadState($sale);

            $sale->generateCodeNumber($sale->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($sale->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($sale->header->date)));
            $codeNumber = CHtml::encode($sale->header->getCodeNumber(SaleHeader::CN_CONSTANT));
            $taxPercentage = $sale->getTaxPercentage();
            $taxValue = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->getCalculatedTax()));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $sale->getGrandTotal()));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
                'taxPercentage' => $taxPercentage,
                'taxValue' => $taxValue,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id)) {
            $sale = new Sale(new SaleHeader(), array());
        } else {
            $saleHeader = $this->loadModel($id);
            $sale = new Sale($saleHeader, $saleHeader->saleDetails);
        }

        return $sale;
    }

    public function loadModel($id) {
        $model = SaleHeader::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        
        return $model;
    }

    protected function loadState($sale) {
        if (isset($_POST['SaleHeader'])) {
            $sale->header->attributes = $_POST['SaleHeader'];
        }
        
        if (isset($_POST['SaleDetail'])) {
            foreach ($_POST['SaleDetail'] as $i => $item) {
                if (isset($sale->details[$i])) {
                    $sale->details[$i]->attributes = $item;
                } else {
                    $detail = new SaleDetail();
                    $detail->attributes = $item;
                    $sale->details[] = $detail;
                }
            }
            
            if (count($_POST['SaleDetail']) < count($sale->details)) {
                array_splice($sale->details, $i + 1);
            }
        } else {
            $sale->details = array();
        }
    }
}