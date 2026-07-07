<?php

class DeliveryController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create'
                || $filterChain->action->id === '/completion/customer'
                || $filterChain->action->id === 'ajaxJsonSale'
                || $filterChain->action->id === 'ajaxHtmlAddProduct'
                || $filterChain->action->id === 'ajaxHtmlRemoveProduct'
                || $filterChain->action->id === 'memo'
                || $filterChain->action->id === 'view') {
            if (!(Yii::app()->user->checkAccess('deliveryCreate') || Yii::app()->user->checkAccess('deliveryEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('deliveryEdit')))
                $this->redirect(array('/site/login'));
        }
        $filterChain->run();
    }

    public function actionCreate() {
        $delivery = $this->instantiate(null);
        $delivery->header->admin_id = Yii::app()->user->id;

        $saleHeader = Search::bind(new SaleHeader('search'), isset($_GET['SaleHeader']) ? $_GET['SaleHeader'] : array());

        $customerCompany = isset($_GET['CustomerCompany']) ? $_GET['CustomerCompany'] : '';

        $saleDataProvider = $saleHeader->searchByDelivery();
        $saleDataProvider->criteria->with = array(
            'customer:resetScope',
            'branch:resetScope',
        );

        $saleDataProvider->criteria->addCondition("customer.company LIKE :company");
        $saleDataProvider->criteria->params[':company'] = "%{$customerCompany}%";

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
            $this->loadState($delivery);
            $delivery->header->branch_id = ($delivery->header->saleHeader === null) ? '' : $delivery->header->saleHeader->branch_id;
            $delivery->generateCodeNumber($delivery->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($delivery->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($delivery->header->date)));

            if ($delivery->save(Yii::app()->db)) {
                Yii::app()->session['DeliveryMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $delivery->header->id));
            }
        }

        $this->render('create', array(
            'delivery' => $delivery,
            'product' => $product,
            'dataProvider' => $dataProvider,
            'saleHeader' => $saleHeader,
            'saleDataProvider' => $saleDataProvider,
            'customerCompany' => $customerCompany,
        ));
    }

    public function actionUpdate($id) {
        $delivery = $this->instantiate($id);
        $delivery->header->admin_id = Yii::app()->user->id;

        $saleHeader = Search::bind(new SaleHeader('search'), isset($_GET['SaleHeader']) ? $_GET['SaleHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $saleHeader->normalizeCnMonthBy($cnMonth);

        $saleDataProvider = $saleHeader->searchByDelivery();
        $saleDataProvider->criteria->with = array(
            'customer:resetScope',
        );

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
            $this->loadState($delivery);
            if ($delivery->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $delivery->header->id));
        }

        $this->render('update', array(
            'delivery' => $delivery,
            'product' => $product,
            'dataProvider' => $dataProvider,
            'saleHeader' => $saleHeader,
            'saleDataProvider' => $saleDataProvider,
            'cnMonth' => strtoupper($cnMonth),
        ));
    }

    public function actionView($id) {
        $delivery = $this->loadModel($id);

        $sale = $delivery->saleHeader(array(
            'scopes' => 'resetScope',
            'with' => array(
                'customer:resetScope',
            ),
                ));

//		$warehouse = $delivery->warehouse(array('scopes' => 'resetScope'));
        $branch = $delivery->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('delivery_header_id', $delivery->id);
        $detailsDataProvider = new CActiveDataProvider('DeliveryDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'delivery' => $delivery,
//			'warehouse' => $warehouse,
            'branch' => $branch,
            'sale' => $sale,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $deliveryHeader = $this->loadModel($id);
            if ($deliveryHeader !== null) {
                $deliveryHeader->is_inactive = ActiveRecord::INACTIVE;
                $deliveryHeader->update(array('is_inactive'));

                foreach ($deliveryHeader->deliveryDetails as $deliveryDetail) {
                    $deliveryDetail->is_inactive = ActiveRecord::INACTIVE;
                    $deliveryDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

//    public function actionMemo($id)
//    {
//        if (!(Yii::app()->user->checkAccess('administrator')))
//        {
//            if (!(isset(Yii::app()->session['DeliveryMemoAllowed']) && Yii::app()->session['DeliveryMemoAllowed'] === true))
//                $this->redirect(array('admin'));
//        }
//
//        Yii::app()->session->remove('DeliveryMemoAllowed');
//
//        $delivery = $this->loadModel($id);
//		
//		$sale = $delivery->saleHeader(array(
//			'scopes' => 'resetScope',
//			'with' => array(
//				'customer:resetScope', 
//			),
//		));
//		
//		$customer = $sale->customer(array('scopes' => 'resetScope'));
//		$warehouse = $delivery->warehouse(array('scopes' => 'resetScope'));
//		$branch = $delivery->branch(array('scopes' => 'resetScope'));
//		$admin = $delivery->admin(array('scopes' => 'resetScope'));
//		
//		$deliveryDetails = $delivery->deliveryDetails(array(
//			'with' => array(
//				'product:resetScope' => array(
//					'with' => 'unit:resetScope',
//				),
//			),
//		));
//
//        $this->render('memo', array(
//            'delivery' => $delivery,
//			'branch' => $branch,
//			'admin' => $admin,
//			'customer' => $customer,
//			'warehouse' => $warehouse,
//			'sale' => $sale,
//			'deliveryDetails' => $deliveryDetails,
//        ));
//    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['DeliveryMemoAllowed']) && Yii::app()->session['DeliveryMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('DeliveryMemoAllowed');

        $deliveryHeader = $this->loadModel($id);

        $this->memoToExcel($deliveryHeader);
    }

    protected function memoToExcel($deliveryHeader) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Surat Jalan');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.5);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.5);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Surat Jalan');

        $worksheet->getColumnDimension('A')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setWidth('8');
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setWidth('2');
        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('7');
        $worksheet->getColumnDimension('D')->setAutoSize(false);
        $worksheet->getColumnDimension('D')->setWidth('6');
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setWidth('2');
        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('12');
        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('12');
        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('H')->setWidth('7');
        $worksheet->getColumnDimension('I')->setAutoSize(false);
        $worksheet->getColumnDimension('I')->setWidth('8');
        $worksheet->getColumnDimension('J')->setAutoSize(false);
        $worksheet->getColumnDimension('J')->setWidth('13');
        $worksheet->getColumnDimension('K')->setAutoSize(false);
        $worksheet->getColumnDimension('K')->setWidth('2');
        $worksheet->getColumnDimension('L')->setAutoSize(false);
        $worksheet->getColumnDimension('L')->setWidth('18');

        $worksheet->getRowDimension('2')->setRowHeight(-1);
        $worksheet->getRowDimension('3')->setRowHeight(-1);
        $worksheet->getRowDimension('9')->setRowHeight(-1);
        
        if ($deliveryHeader->branch_id != 4) {
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($worksheet);
            $objDrawingPType->setName("Logo");
            $objDrawingPType->setPath(Yii::app()->basePath . "/images/logo/logo" . $deliveryHeader->branch->id . ".jpg");
            $objDrawingPType->setCoordinates('A1');
            $objDrawingPType->setWidth(85);
            $objDrawingPType->setResizeProportional(true);
            $objDrawingPType->setOffsetX(0);
            $objDrawingPType->setOffsetY(3);
        }

        $worksheet->getStyle('D2:I2')->getFont()->setBold(true);
        $worksheet->getStyle('D2:I2')->getFont()->setSize(18);
        $worksheet->mergeCells('D2:I2');

        if ((int) $deliveryHeader->branch_id !== 4) {
            $worksheet->setCellValue('D2', $deliveryHeader->branch->name);
            $worksheet->getStyle('D2')->getFont()->setName('Bodoni MT Poster Compressed');
        }

        $styleArray = array(
            'font' => array(
                'underline' => 'single',
                'size' => 16,
                'name' => 'Arial'
            )
        );

        $worksheet->mergeCells('J2:L2');
        $worksheet->getStyle('J2:L2')->getFont()->setBold(true);
        $worksheet->setCellValue('J2', 'SURAT JALAN');
        $objPHPExcel->getActiveSheet()->getStyle("J2")->applyFromArray($styleArray);

//        $worksheet->getStyle("D3:L5")->getFont()->setSize(10);
        $worksheet->mergeCells("D3:I3");
        $worksheet->getStyle("D3:I3")->getAlignment()->setWrapText(true);
        $worksheet->setCellValue("D3", strip_tags(nl2br(CHtml::encode($deliveryHeader->branch->address))));

        $worksheet->setCellValue("J3", 'Tanggal');
        $worksheet->setCellValue("K3", ':');
        $worksheet->setCellValue("L3", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($deliveryHeader->date)));

        $worksheet->setCellValue("D4", 'Telp');
        $worksheet->setCellValue("E4", ':');
        $worksheet->mergeCells("F4:H4");
        $worksheet->setCellValue("F4", $deliveryHeader->branch->phone);

        $worksheet->setCellValue("J4", 'No Surat Jalan');
        $worksheet->setCellValue("K4", ':');
        $worksheet->getStyle("L4")->getFont()->setBold(true);
        $worksheet->setCellValue("L4", $deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT));

        $worksheet->setCellValue("D5", 'NPWP');
        $worksheet->setCellValue("E5", ':');
        $worksheet->mergeCells("F5:H5");
        $worksheet->setCellValue("F5", $deliveryHeader->branch->npwp);
        
        $worksheet->setCellValue("J5", 'No PO');
        $worksheet->setCellValue("K5", ':');
        $worksheet->setCellValue("L5", $deliveryHeader->saleHeader->reference);
        
        $worksheet->setCellValue("A7", 'Kepada');
        $worksheet->setCellValue("B7", ':');

        $worksheet->mergeCells("A8:L8");
        $worksheet->setCellValue("A8", $deliveryHeader->saleHeader->customer->company);

        $worksheet->mergeCells("A9:L9");
        $worksheet->getStyle("A9:L9")->getAlignment()->setWrapText(true);
        $worksheet->setCellValue("A9", strip_tags(nl2br(CHtml::encode($deliveryHeader->saleHeader->customer->address))));

        $worksheet->mergeCells('B10:G10');
        $worksheet->mergeCells('J10:L10');
        $worksheet->getStyle('A10:L10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A10:L10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A10:L10')->getFont()->setBold(true);
        $worksheet->setCellValue('A10', 'No.');
        $worksheet->setCellValue('B10', 'Nama Barang');
        $worksheet->setCellValue('H10', 'Qty');
        $worksheet->setCellValue('I10', 'Satuan');
        $worksheet->setCellValue('J10', 'Keterangan');

        $counter = 11;
        $itemNumber = 1;
        foreach ($deliveryHeader->deliveryDetails as $i => $detail) {
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("J{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:L{$counter}");
            $worksheet->setCellValue("A{$counter}", $itemNumber);
            $worksheet->setCellValue("B{$counter}", CHtml::value($detail, 'saleDetail.product_name'));
            $worksheet->setCellValue("H{$counter}", CHtml::value($detail, 'quantity'));
            $worksheet->setCellValue("I{$counter}", CHtml::value($detail, 'productUnit'));

            $counter++;
            $itemNumber++;
        }

        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--) {
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("J{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("L{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->mergeCells("B{$counter}:G{$counter}");
            $worksheet->mergeCells("J{$counter}:L{$counter}");
            $counter++;
        }

        $worksheet->getStyle("A{$counter}:L{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $counter++;

        $worksheet->mergeCells("A{$counter}:G{$counter}");
        $worksheet->mergeCells("H{$counter}:L{$counter}");
        $worksheet->getStyle("A{$counter}:L{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:L{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'Tanda Terima,');
        $worksheet->setCellValue("H{$counter}", 'Hormat kami,');

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="surat jalan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionAdmin() {
        $delivery = Search::bind(new DeliveryHeader('search'), isset($_GET['DeliveryHeader']) ? $_GET['DeliveryHeader'] : array());
        $delivery->customerName = isset($_GET['DeliveryHeader']['customerName']) ? $_GET['DeliveryHeader']['customerName'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        
        $dataProvider = $delivery->searchWithPaging();

        //date filter
        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'delivery' => $delivery,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionAjaxJsonSale($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $delivery = $this->instantiate($id);

            $this->loadState($delivery);

            $sale = $delivery->header->saleHeader(array('scopes' => 'resetScope', 'with' => 'customer:resetScope'));
            $customer = $sale->customer(array('scopes' => 'resetScope'));

            $delivery->generateCodeNumber($sale->branch_id, date('m'), date('y'));

            $object = array(
                'sale_header_codeNumber' => $sale->getCodeNumber(SaleHeader::CN_CONSTANT),
                'delivery_header_codeNumber' => $delivery->header->getCodeNumber(DeliveryHeader::CN_CONSTANT),
                'customer_company' => $customer->company,
                'branch' => $sale->branch->name,
                'reference' => $sale->reference,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlAddProduct($id, $nt) {
        if (Yii::app()->request->isAjaxRequest) {
            $delivery = $this->instantiate($id);

            $this->loadState($delivery);

            if (!isset($_POST['DeliveryDetail']))
                $delivery->details = array();

            if (isset($_POST['DeliveryHeader']['sale_header_id']))
                $delivery->addDetail($_POST['DeliveryHeader']['sale_header_id']);

            $this->renderPartial('_detail', array(
                'delivery' => $delivery,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $delivery = $this->instantiate($id);

            $this->loadState($delivery);

            $delivery->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'delivery' => $delivery,
            ));
        }
    }

    public function actionAjaxHtmlUpdateAllProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $delivery = $this->instantiate($id);

            $this->loadState($delivery);

            $this->renderPartial('_detail', array(
                'delivery' => $delivery,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $delivery = $this->instantiate($id);
            $this->loadState($delivery);

            $delivery->generateCodeNumber($delivery->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($delivery->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($delivery->header->date)));
            $codeNumber = CHtml::encode($delivery->header->getCodeNumber(DeliveryHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $delivery = new Delivery(new DeliveryHeader(), array());
        else {
            $deliveryHeader = $this->loadModel($id);
            $delivery = new Delivery($deliveryHeader, $deliveryHeader->deliveryDetails);
        }

        return $delivery;
    }

    public function loadModel($id) {
        $model = DeliveryHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($delivery) {
        if (isset($_POST['DeliveryHeader'])) {
            $delivery->header->attributes = $_POST['DeliveryHeader'];
        }
        if (isset($_POST['DeliveryDetail'])) {
            foreach ($_POST['DeliveryDetail'] as $i => $item) {
                if (isset($delivery->details[$i]))
                    $delivery->details[$i]->attributes = $item;
                else {
                    $detail = new DeliveryDetail();
                    $detail->attributes = $item;
                    $delivery->details[] = $detail;
                }
            }
            if (count($_POST['DeliveryDetail']) < count($delivery->details))
                array_splice($delivery->details, $i + 1);
        }
        else
            $delivery->details = array();
    }

}
