<?php

class SaleInvoiceController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view' || $filterChain->action->id === 'create' || $filterChain->action->id === 'ajaxJsonDelivery' || $filterChain->action->id === 'ajaxHtmlShowDelivery' || $filterChain->action->id === 'taxForm' || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('saleInvoiceCreate') || Yii::app()->user->checkAccess('saleInvoiceEdit')))
                $this->redirect(array('/site/login'));
        }

        if ($filterChain->action->id === 'admin' || $filterChain->action->id === 'update' || $filterChain->action->id === 'delete')
            if (!(Yii::app()->user->checkAccess('saleInvoiceEdit')))
                $this->redirect(array('/site/login'));

        $filterChain->run();
    }

    public function actionCreate() {
        $saleInvoice = $this->instantiate(null);

        $saleInvoice->header->admin_id = Yii::app()->user->id;
        $deliveryHeader = Search::bind(new DeliveryHeader('search'), isset($_GET['DeliveryHeader']) ? $_GET['DeliveryHeader'] : array());

        $customerCompany = isset($_GET['CustomerCompany']) ? $_GET['CustomerCompany'] : '';

        $dataProvider = $deliveryHeader->searchByInvoice($saleInvoice->header->is_non_tax);
        $dataProvider->criteria->with = array(
            'saleHeader:resetScope' => array(
                'with' => 'customer:resetScope'
            ),
        );

        $dataProvider->criteria->addCondition("customer.company LIKE :company");
        $dataProvider->criteria->params[':company'] = "%{$customerCompany}%";

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleInvoice);
            $saleInvoice->header->branch_id = ($saleInvoice->header->deliveryHeader === null) ? '' : $saleInvoice->header->deliveryHeader->branch_id;
            $saleInvoice->generateCodeNumber($saleInvoice->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleInvoice->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleInvoice->header->date)));

            if ($saleInvoice->save(Yii::app()->db)) {
                Yii::app()->session['SaleInvoiceMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleInvoice->header->id));
            }
        }

        $this->render('create', array(
            'saleInvoice' => $saleInvoice,
            'deliveryHeader' => $deliveryHeader,
            'dataProvider' => $dataProvider,
            'customerCompany' => $customerCompany,
        ));
    }

    public function actionUpdate($id) {
        $saleInvoice = $this->instantiate($id);

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleInvoice);

            if ($saleInvoice->save(Yii::app()->db)) {
                Yii::app()->session['SaleInvoiceMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleInvoice->header->id));
            }
        }

        $this->render('update', array(
            'saleInvoice' => $saleInvoice,
        ));
    }

    public function actionView($id) {
        $saleInvoice = $this->loadModel($id);

        $deliveryHeader = $saleInvoice->deliveryHeader(array(
            'scopes' => 'resetScope',
            'with' => 'saleHeader:resetScope',
        ));

        $branch = $saleInvoice->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_invoice_id', $saleInvoice->id);

        $this->render('view', array(
            'saleInvoice' => $saleInvoice,
            'deliveryHeader' => $deliveryHeader,
            'branch' => $branch,
        ));
    }

    public function actionAdmin() {
        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());
        $customerCompany = (isset($_GET['CustomerCompany'])) ? $_GET['CustomerCompany'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $saleInvoice->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'deliveryHeader:resetScope' => array(
                'with' => array(
                    'saleHeader:resetScope' => array(
                        'with' => 'customer:resetScope'
                    ),
                ),
            ),
            'branch:resetScope',
        );

        $saleInvoice->customerCompany = $customerCompany;
        $dataProvider->criteria->compare('customer.company', $customerCompany, true);

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'saleInvoice' => $saleInvoice,
            'dataProvider' => $dataProvider,
            'customerCompany' => $customerCompany,
        ));
    }

    public function actionAdminUnprocessed() {
        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());
        $customerCompany = (isset($_GET['CustomerCompany'])) ? $_GET['CustomerCompany'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $saleInvoice->resetScope()->searchByUnprocessedReceipt();
        $dataProvider->criteria->with = array(
            'deliveryHeader' => array(
                'with' => array(
                    'saleHeader' => array(
                        'with' => 'customer:resetScope'
                    ),
                ),
            ),
            'branch:resetScope',
        );

        $saleInvoice->customerCompany = $customerCompany;
        $dataProvider->criteria->compare('customer.company', $customerCompany, true);

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('adminUnprocessed', array(
            'saleInvoice' => $saleInvoice,
            'dataProvider' => $dataProvider,
            'customerCompany' => $customerCompany,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['SaleInvoiceMemoAllowed']) && Yii::app()->session['SaleInvoiceMemoAllowed'] === true)) {
                $this->redirect(array('admin'));
            }
        }

        Yii::app()->session->remove('SaleInvoiceMemoAllowed');

        $saleInvoice = $this->loadModel($id);

//		$this->render('memo', array(
//            'saleInvoice' => $saleInvoice,
//        ));
        $this->memoToExcel($saleInvoice);
    }

    public function actionAjaxJsonGrandTotal($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleInvoice = $this->instantiate($id);

            $this->loadState($saleInvoice);

            $tax = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleInvoice->calculatedTax));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleInvoice->grandTotal));

            echo CJSON::encode(array(
                'tax' => $tax,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $saleInvoice = $this->loadModel($id);

            if ($saleInvoice !== null) {
                $dbTransaction = Yii::app()->db->beginTransaction();
                try {
                    $saleInvoice->is_inactive = ActiveRecord::INACTIVE;
                    $valid = $saleInvoice->update(array('is_inactive'));

                    $valid = $valid && JournalAccounting::model()->deleteAllByAttributes(array(
                                'transaction_number' => $saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT),
                                'branch_id' => $saleInvoice->branch_id
                    ));

                    if ($valid) {
                        $dbTransaction->commit();
                        Yii::app()->user->setFlash('message', 'Delete Successful');
                    } else {
                        $dbTransaction->rollback();
                        Yii::app()->user->setFlash('message', 'Delete Failed');
                    }
                } catch (Exception $e) {
                    $dbTransaction->rollback();
                    Yii::app()->user->setFlash('message', $e->getMessage());
                }
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionTaxform($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['SaleInvoiceMemoAllowed']) && Yii::app()->session['SaleInvoiceMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('SaleInvoiceMemoAllowed');

        $saleInvoice = $this->loadModel($id);

//        $this->taxFormToExcel($saleInvoice);         //export to excel
        $deliveryHeader = $saleInvoice->deliveryHeader(array(
            'scopes' => 'resetScope',
            'with' => 'saleHeader:resetScope',
        ));

        $branch = $saleInvoice->branch(array('scopes' => 'resetScope'));

        $this->render('taxform', array(
            'saleInvoice' => $saleInvoice,
            'deliveryHeader' => $deliveryHeader,
            'branch' => $branch,
        ));
    }

    public function actionAjaxHtmlShowDelivery($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleInvoice = $this->instantiate($id);

            $this->loadState($saleInvoice);

            $delivery = DeliveryHeader::model()->findByPk(isset($_POST['SaleInvoice']['delivery_header_id']) ? $_POST['SaleInvoice']['delivery_header_id'] : '');

            if ($delivery === null)
                $delivery = DeliveryHeader::model();

            $saleInvoice->header->shipping_fee = $delivery->saleHeader->shipping_fee;
            $saleInvoice->header->discount = $delivery->saleHeader->discount;

            $this->renderPartial('_detail', array(
                'saleInvoice' => $saleInvoice,
                'delivery' => $delivery,
            ));
        }
    }

    public function actionAjaxJsonDelivery($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleInvoice = $this->instantiate($id);

            $this->loadState($saleInvoice);

            $saleInvoiceSale = $saleInvoice->header->deliveryHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'saleHeader:resetScope',
                ),
            ));

            $saleInvoice->generateCodeNumber($saleInvoiceSale->branch_id, date('m'), date('y'));

            $object = array(
                'delivery_header_codeNumber' => $saleInvoiceSale->getCodeNumber(DeliveryHeader::CN_CONSTANT),
                'sale_invoice_codeNumber' => $saleInvoice->header->getCodeNumberInvoice(SaleInvoice::CN_CONSTANT, $saleInvoice->header->deliveryHeader->cn_ordinal, $saleInvoice->header->deliveryHeader->cn_month, $saleInvoice->header->deliveryHeader->cn_year),
                'codeNumber' => $saleInvoice->header->codeNumber,
                'customer_company' => $saleInvoiceSale->saleHeader->customer->company,
                'branch' => $saleInvoiceSale->branch->name,
                'taxPercentage' => $saleInvoiceSale->saleHeader->tax,
            );

            echo CJSON::encode($object);
        }
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->deliveryHeader->grandTotal;

        return $grandTotal;
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleInvoice = $this->instantiate($id);
            $this->loadState($saleInvoice);

            $saleInvoice->generateCodeNumber($saleInvoice->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleInvoice->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleInvoice->header->date)));
            $codeNumber = CHtml::encode($saleInvoice->header->getCodeNumber(SaleInvoice::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    protected function memoToExcel($saleInvoice) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Invoice');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Invoice');

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
        $image = Yii::app()->basePath . "/images/logo/logo" . $saleInvoice->branch->id . ".jpg";
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

        if ((int) $saleInvoice->branch_id !== 4) {
            $worksheet->setCellValue("D{$counter}", $saleInvoice->branch->name);
        }
        
        $worksheet->mergeCells("J{$counter}:M{$counter}");
        $worksheet->getStyle("J{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("J{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $worksheet->setCellValue("J{$counter}", 'Faktur Penjualan');
        $objPHPExcel->getActiveSheet()->getStyle("J{$counter}")->applyFromArray($styleArray);

        $counter ++;
        if ((int) $saleInvoice->branch_id !== 4) {
            $worksheet->getStyle("D{$counter}:I{$counter}")->getAlignment()->setWrapText(true);
            $worksheet->mergeCells("D{$counter}:I{$counter}");
            $worksheet->setCellValue("D{$counter}", $saleInvoice->branch->address);

            $counter ++;
            $worksheet->setCellValue("D{$counter}", 'Telp');
            $worksheet->setCellValue("E{$counter}", ':');
            $worksheet->mergeCells("F{$counter}:I{$counter}");
            $worksheet->getStyle("F{$counter}")->getFont()->setSize(10);
            $worksheet->setCellValue("F{$counter}", $saleInvoice->branch->phone);
        }
        
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'Tgl Faktur');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->setCellValue("M{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($saleInvoice->date)));

        $counter++;
        if ((int) $saleInvoice->branch_id !== 4) {
            $worksheet->setCellValue("D{$counter}", 'NPWP');
            $worksheet->setCellValue("E{$counter}", ':');
            $worksheet->mergeCells("F{$counter}:H{$counter}");
            $worksheet->setCellValue("F{$counter}", $saleInvoice->branch->npwp);
        }
        
        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'No Faktur');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("M{$counter}", $saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT));

        $counter++;
        $worksheet->setCellValue("A{$counter}", 'Kepada');

        $counter++;
        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $worksheet->setCellValue("A{$counter}", $saleInvoice->deliveryHeader->saleHeader->customer->company);

        $worksheet->mergeCells("J{$counter}:K{$counter}");
        if ($saleInvoice->branch_id != 4) {
            $worksheet->setCellValue("J{$counter}", 'No Faktur Pajak');
            $worksheet->setCellValue("L{$counter}", ':');
            $worksheet->setCellValue("M{$counter}", $saleInvoice->reference);
        }
        
        $counter++;
        $counter_3 = $counter + 1;
        $worksheet->mergeCells("A{$counter}:I{$counter_3}");
        $worksheet->getStyle("A{$counter}:B{$counter_3}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $worksheet->getStyle("A{$counter}:B{$counter_3}")->getAlignment()->setWrapText(true);
        $worksheet->setCellValue("A{$counter}", strip_tags(nl2br($saleInvoice->deliveryHeader->saleHeader->customer->address)));

        $worksheet->mergeCells("J{$counter}:K{$counter}");
        $worksheet->setCellValue("J{$counter}", 'No PO');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->setCellValue("M{$counter}", CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.reference'));

        $counter++;
        $counter++;
        $worksheet->mergeCells("A{$counter}:G{$counter}");
        $worksheet->setCellValue("A{$counter}", $saleInvoice->deliveryHeader->saleHeader->customer->npwp);

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
        foreach ($saleInvoice->deliveryHeader->deliveryDetails as $detail) {
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
            $worksheet->setCellValue("B{$counter}", $detail->saleDetail->product_name);
            $worksheet->setCellValue("H{$counter}", $detail->quantity);
            $worksheet->setCellValue("I{$counter}", $detail->productUnit);
            $worksheet->setCellValue("J{$counter}", Yii::app()->numberFormatter->format('#,##0', $detail->getUnitPrice()));
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
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->subTotal));

        $counter++;
        if ($saleInvoice->branch_id != 4) {
            $worksheet->setCellValue("A{$counter}", 'Keterangan:');
        }
        $worksheet->setCellValue("J{$counter}", 'DPP lain-lain');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->costOfGoodsSold));

        $counter++;
        
        if ($saleInvoice->branch_id != 4) {
            $worksheet->setCellValue("A{$counter}", 'Pembayaran a/n ' . $saleInvoice->branch->name);
        }
        $worksheet->setCellValue("J{$counter}", 'Disc');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->discount));

        $counter++;
        
        $worksheet->setCellValue("A{$counter}", $saleInvoice->branch->bank_account);
        if ($saleInvoice->branch_id != 4) {
            $worksheet->setCellValue("J{$counter}", 'PPN');
            $worksheet->setCellValue("L{$counter}", ':');
            $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->calculatedTax));
        } else {
            $worksheet->setCellValue("J{$counter}", 'Ongkos Kirim');
            $worksheet->setCellValue("L{$counter}", ':');
            $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->shipping_fee));
        }

        $counter++;
        
        $worksheet->setCellValue("J{$counter}", 'Grand Total');
        $worksheet->setCellValue("L{$counter}", ':');
        $worksheet->getStyle("M{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("M{$counter}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->grandTotal));

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment;filename="invoice.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionExcel($id) {
        $saleInvoice = $this->loadModel($id);

        $this->taxFormToExcel($saleInvoice);
    }

    public function taxFormToExcel($saleInvoice) {
        /* initialization */
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Tax Form');

        /* sheet */
        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Tax Form');
        $worksheet->getColumnDimension('B')->setWidth(6);

        /* variables */
        $cellRowNumber = 1;
        $arrayIndex = 0;              //for array checkpoint if detail is too long
        $cellRowLimit = 34;              //for setting the maximum cells before printing footer. Value 32 is for 'letter' paper size
        $count = count($saleInvoice->deliveryHeader->deliveryDetails);
        $pageSize = 16;               //minimum detail row. Will add empty row if detail has ended
        $cellAdded = 0;               //for adding cellRowLimit after each loop

        while ($arrayIndex < $count) {
            /* sheet header */
            $cellRowNumber2 = $cellRowNumber + 4;

            $worksheet->getStyle("A{$cellRowNumber}:F{$cellRowNumber2}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //title
            $worksheet->getStyle("A{$cellRowNumber}:F{$cellRowNumber2}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //title
            $worksheet->getStyle("A{$cellRowNumber}:F{$cellRowNumber2}")->getFont()->setBold(true); //title
            $worksheet->getStyle("A{$cellRowNumber}:F{$cellRowNumber2}")->getFont()->setSize(22); //title
            $worksheet->mergeCells("A{$cellRowNumber}:F{$cellRowNumber2}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Faktur Pajak');

            /* header box */
            $worksheet->getStyle("G{$cellRowNumber}")->getFont()->setSize(8); //header box
            $worksheet->getStyle("H{$cellRowNumber}")->getFont()->setSize(8); //header box

            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("G{$cellRowNumber}", 'Lembar ke-1:');
            $worksheet->setCellValue("H{$cellRowNumber}", 'Untuk pembeli BKP / penerima JKP');

            $cellRowNumber2 = $cellRowNumber;
            $cellRowNumber++;

            $worksheet->getStyle("H{$cellRowNumber}")->getFont()->setSize(8); //header box

            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("H{$cellRowNumber}", 'sebagai bukti pajak masukan');
            $cellRowNumber++;

            $worksheet->getStyle("G{$cellRowNumber}")->getFont()->setSize(8); //header box
            $worksheet->getStyle("H{$cellRowNumber}")->getFont()->setSize(8); //header box

            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("G{$cellRowNumber}", 'Lembar ke-2:');
            $worksheet->setCellValue("H{$cellRowNumber}", 'Untuk PKP sebagai bukti pajak keluaran');
            $cellRowNumber++;

            $worksheet->getStyle("G{$cellRowNumber}")->getFont()->setSize(8); //header box
            $worksheet->getStyle("H{$cellRowNumber}")->getFont()->setSize(8); //header box

            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("G{$cellRowNumber}", 'Lembar ke-3:');
            $worksheet->setCellValue("H{$cellRowNumber}", 'Untuk arsip / file');

            $worksheet->getStyle("G{$cellRowNumber2}:J{$cellRowNumber}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); //header box border

            $cellRowNumber++;

            /* stakeholders */

            /* stakeholder header */
            $cellRowNumber++;
            $worksheet->getStyle("A{$cellRowNumber}:J{$cellRowNumber}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //stakeholder border
            $worksheet->getStyle("A{$cellRowNumber}:D{$cellRowNumber}")->getFont()->setBold(true); //stakeholder header font

            $worksheet->mergeCells("A{$cellRowNumber}:C{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Kode dan Nomor Seri :');
            $worksheet->mergeCells("D{$cellRowNumber}:F{$cellRowNumber}");
            $worksheet->setCellValue("D{$cellRowNumber}", $saleInvoice->reference);
            $worksheet->mergeCells("G{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("G{$cellRowNumber}", 'Invoice : ' . $saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT));
            $cellRowNumber++;

            /* stakeholder supplier */
            $cellRowNumberSupplier = $cellRowNumber;
            $worksheet->getStyle("A{$cellRowNumberSupplier}:J{$cellRowNumberSupplier}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //stakeholder supplier border
            $cellRowNumberSupplier++;
            $cellRowNumberSupplier--; //value neutralizer for font design
            $worksheet->getStyle("A{$cellRowNumberSupplier}:J{$cellRowNumberSupplier}")->getFont()->setBold(true); //stakeholder supplier font

            $worksheet->mergeCells("A{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Pengusaha kena pajak');
            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'Nama');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->branch->name);
            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'Alamat');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $addressLength = strlen($saleInvoice->branch->address);     //auto merge cell for long address
            $cellRowNumberSupplierAddressEnd = $cellRowNumber;
            while ($addressLength > 80) {
                $cellRowNumberSupplierAddressEnd++;
                $addressLength -= 80;
            }
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumberSupplierAddressEnd}");
            if ($cellRowNumberSupplierAddressEnd > $cellRowNumber)         //if address is long, merge cells and wrap text
                $worksheet->getStyle("C{$cellRowNumber}")->getAlignment()->setWrapText(true);

            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->branch->address . '.');
            $cellRowNumber = $cellRowNumberSupplierAddressEnd;
            $cellRowNumberSupplierAddressEnd++;
            $worksheet->getStyle("A{$cellRowNumberSupplier}:J{$cellRowNumberSupplierAddressEnd}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //stakeholder supplier border

            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'NPWP');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->branch->npwp);
            $cellRowNumber++;

            /* stakeholder customer */
            $cellRowNumberCustomer = $cellRowNumber;
            $worksheet->getStyle("A{$cellRowNumberCustomer}:J{$cellRowNumberCustomer}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //stakeholder customer
            $cellRowNumberCustomer++;
            $cellRowNumberCustomer--; //value neutralizer for font deisgn
            $worksheet->getStyle("A{$cellRowNumberCustomer}:J{$cellRowNumberCustomer}")->getFont()->setBold(true); //stakeholder customer font

            $worksheet->mergeCells("A{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Pembeli barang kena pajak / Penerima jasa kena pajak');
            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'Nama');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->deliveryHeader->saleHeader->customer->company);
            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'Alamat');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $addressLength = strlen($saleInvoice->deliveryHeader->saleHeader->customer->address); //auto merge cell for long address
            $cellRowNumberCustomerAddressEnd = $cellRowNumber;
            while ($addressLength > 80) {
                $cellRowNumberCustomerAddressEnd++;
                $addressLength -= 80;
            }
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumberCustomerAddressEnd}");
            if ($cellRowNumberCustomerAddressEnd > $cellRowNumber)         //if address is long wrap text
                $worksheet->getStyle("C{$cellRowNumber}")->getAlignment()->setWrapText(true);

            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->deliveryHeader->saleHeader->customer->address . '.');
            $cellRowNumber = $cellRowNumberCustomerAddressEnd;
            $cellRowNumberCustomerAddressEnd++;
            $worksheet->getStyle("A{$cellRowNumberCustomer}:J{$cellRowNumberCustomerAddressEnd}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //stakeholder customer

            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", 'NPWP');
            $worksheet->setCellValue("B{$cellRowNumber}", ':');
            $worksheet->mergeCells("C{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("C{$cellRowNumber}", $saleInvoice->deliveryHeader->saleHeader->customer->npwp);
            $cellRowNumber++;

            /* goods list */
            /* table header */
            $cellRowNumberTableHeader = $cellRowNumber;
            $cellRowNumberTableHeaderPlusOne = $cellRowNumber + 1;
            $worksheet->mergeCells("A{$cellRowNumber}:A{$cellRowNumberTableHeaderPlusOne}");
            $worksheet->mergeCells("B{$cellRowNumber}:G{$cellRowNumberTableHeaderPlusOne}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumberTableHeaderPlusOne}");
            $worksheet->getStyle("A{$cellRowNumberTableHeader}:J{$cellRowNumberTableHeaderPlusOne}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table header border
            $worksheet->getStyle("A{$cellRowNumberTableHeader}:J{$cellRowNumberTableHeaderPlusOne}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table header border
            $worksheet->getStyle("A{$cellRowNumberTableHeader}:J{$cellRowNumberTableHeaderPlusOne}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //table header alignment
            $worksheet->getStyle("A{$cellRowNumberTableHeader}:J{$cellRowNumberTableHeaderPlusOne}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //table header alignment
            $worksheet->getStyle("A{$cellRowNumberTableHeader}:J{$cellRowNumberTableHeaderPlusOne}")->getFont()->setBold(true); //table header font

            $worksheet->getStyle("H{$cellRowNumber}")->getAlignment()->setWrapText(true); //table header
            $worksheet->setCellValue("A{$cellRowNumber}", 'No. urut');
            $worksheet->setCellValue("B{$cellRowNumber}", 'Nama barang / jasa kena pajak');
            $worksheet->setCellValue("H{$cellRowNumber}", 'Harga jual / penggantian / uang muka / termin (Rp.)');
            $cellRowNumber += 2; //+2 because we merge 2 rows for table header

            /* table content */
            $cellRowNumberTableContent = $cellRowNumber;

            /* valued cells */
            if ($arrayIndex != 0) {
                $cellRowLimit = $cellRowLimit + 46 + $cellAdded;            //value 46 = length from header start to footer start. In this case, cell row number before "Total Harga jual / penggantian ..." which is 78
            }

            $counter = 0;
            while ($arrayIndex < $count && $cellRowNumber < $cellRowLimit) {
                $worksheet->mergeCells("B{$cellRowNumber}:G{$cellRowNumber}");
                $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
                $worksheet->setCellValue("A{$cellRowNumber}", $arrayIndex + 1);
                $worksheet->setCellValue("B{$cellRowNumber}", $saleInvoice->deliveryHeader->saleHeader->saleDetails[$arrayIndex]->product_name);
                $worksheet->setCellValue("H{$cellRowNumber}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->deliveryDetails[$arrayIndex]->total));

                $arrayIndex++;
                $cellRowNumber++;
                $counter++;
            }

            /* empty cells */
            for ($i = 0; $i < $pageSize - $counter; $i++) {
                $worksheet->mergeCells("B{$cellRowNumber}:G{$cellRowNumber}");
                $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
                $worksheet->setCellValue("A{$cellRowNumber}", '');
                $worksheet->setCellValue("B{$cellRowNumber}", '');
                $worksheet->setCellValue("H{$cellRowNumber}", '');

                $cellRowNumber++;
            }
            $cellRowNumberTableFillerEnd = $cellRowNumber - 1;
            $worksheet->getStyle("A{$cellRowNumberTableContent}:J{$cellRowNumberTableFillerEnd}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table content border
            $worksheet->getStyle("A{$cellRowNumberTableContent}:J{$cellRowNumberTableFillerEnd}")->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table content border
            $worksheet->getStyle("A{$cellRowNumberTableContent}:A{$cellRowNumberTableFillerEnd}")->getAlignment()->setHorizontal('center'); //table content+column number alignment

            /* table footer */
            $cellRowNumberTableFooter = $cellRowNumber;
            $cellRowNumberTableFooterEnd = $cellRowNumberTableFooter + 4;
            $worksheet->getStyle("H{$cellRowNumberTableContent}:J{$cellRowNumberTableFooterEnd}")->getAlignment()->setHorizontal('right'); //table content+footer rightmost column alignment
            $worksheet->getStyle("A{$cellRowNumberTableFooter}:J{$cellRowNumberTableFooterEnd}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table footer border
            $worksheet->getStyle("A{$cellRowNumberTableFooter}:J{$cellRowNumberTableFooterEnd}")->getBorders()->getInside()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM); //table footer border
            $worksheet->getStyle("B{$cellRowNumberTableFooter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE); //table footer border
            $worksheet->getStyle("C{$cellRowNumberTableFooter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE); //table footer border

            $worksheet->mergeCells("A{$cellRowNumber}:G{$cellRowNumber}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Total harga jual / penggantian / uang muka / termin *)');
            $worksheet->setCellValue("H{$cellRowNumber}", Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->subTotal));
            $cellRowNumber++;

            $worksheet->mergeCells("A{$cellRowNumber}:G{$cellRowNumber}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Potongan harga');
            $worksheet->setCellValue("H{$cellRowNumber}", isset($saleInvoice->deliveryHeader->saleHeader->discount) ? '-' . Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->saleHeader->discount) : '0');
            $cellRowNumber++;

            $worksheet->mergeCells("A{$cellRowNumber}:G{$cellRowNumber}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Uang muka yang telah diterima');
            $worksheet->setCellValue("H{$cellRowNumber}", isset($saleInvoice->deliveryHeader->saleHeader->saleDownpayment->amount) ? '-' . Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->saleHeader->saleDownpayment->amount) : '0');
            $cellRowNumber++;

            $worksheet->mergeCells("A{$cellRowNumber}:G{$cellRowNumber}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Dasar pengenaan pajak');
            $worksheet->setCellValue("H{$cellRowNumber}", isset($saleInvoice->deliveryHeader->saleHeader->totalBeforeTax) ? Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->totalBeforeTax) : '0');
            $cellRowNumber++;

            $worksheet->mergeCells("A{$cellRowNumber}:G{$cellRowNumber}");
            $worksheet->mergeCells("H{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'PPN 10% dari dasar pengenaan pajak');
            $worksheet->setCellValue("H{$cellRowNumber}", isset($saleInvoice->deliveryHeader->saleHeader->calculatedTax) ? Yii::app()->numberFormatter->format('#,##0', $saleInvoice->deliveryHeader->calculatedTax) : '0');
            $cellRowNumber++;

            /* information */
            $cellRowNumber++;
            $worksheet->mergeCells("A{$cellRowNumber}:F{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Pajak penjualan atas barang mewah');
            $cellRowNumber++;

            /* table information header */
            $cellRowNumberInformation = $cellRowNumber;
            $worksheet->getStyle("A{$cellRowNumberInformation}:D{$cellRowNumberInformation}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); //table information header
            $cellRowNumberInformationEnd = $cellRowNumberInformation + 5;
            $worksheet->getStyle("A{$cellRowNumberInformationEnd}:D{$cellRowNumberInformationEnd}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); //table information header
            $worksheet->getStyle("A{$cellRowNumberInformation}:D{$cellRowNumberInformationEnd}")->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); //table information header
            $worksheet->getStyle("A{$cellRowNumberInformation}:D{$cellRowNumberInformationEnd}")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); //table information header

            $worksheet->mergeCells("B{$cellRowNumber}:C{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Tarif');
            $worksheet->setCellValue("B{$cellRowNumber}", 'DPP');
            $worksheet->setCellValue("D{$cellRowNumber}", 'PPnBM');
            $cellRowNumber++;

            for ($i = 0; $i < 4; $i++) {
                $worksheet->mergeCells("B{$cellRowNumber}:C{$cellRowNumber}");
                $worksheet->setCellValue("A{$cellRowNumber}", '.....%');
                $worksheet->setCellValue("B{$cellRowNumber}", 'Rp.....');
                $worksheet->setCellValue("D{$cellRowNumber}", 'Rp.....');
                $cellRowNumber++;
            }
            $worksheet->mergeCells("B{$cellRowNumber}:C{$cellRowNumber}");
            $worksheet->setCellValue("A{$cellRowNumber}", 'Jumlah');
            $worksheet->setCellValue("B{$cellRowNumber}", '');
            $worksheet->setCellValue("D{$cellRowNumber}", 'Rp.....');
            $cellRowNumber++;

            $worksheet->setCellValue("A{$cellRowNumber}", '*)Coret yang tidak perlu');
            $cellRowNumber++;

            /* signature */

            $cellRowNumber -= 8;
            $cellRowNumberSignature = $cellRowNumber;
            $worksheet->mergeCells("G{$cellRowNumber}:J{$cellRowNumber}");
            setlocale(LC_TIME, 'ind');            //set time to indonesia and use strftime()
            $worksheet->setCellValue("G{$cellRowNumber}", 'Jakarta, ' . strftime('%#d %B %Y', strtotime($saleInvoice->deliveryHeader->saleHeader->date)));
            $worksheet->getStyle("G{$cellRowNumberSignature}")->getAlignment()->setHorizontal('center'); //signature date alignment

            $cellRowNumber += 6;
            $worksheet->mergeCells("G{$cellRowNumber}:J{$cellRowNumber}");
            $worksheet->setCellValue("G{$cellRowNumber}", 'Ivanov Alexander');
            $worksheet->getStyle("G{$cellRowNumber}")->getAlignment()->setHorizontal('center'); //signature name alignment

            $cellAdded++;
            $cellRowNumber += 2;             //beginning of new page
        }

        /* export to excel */
        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="taxForm' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($saleInvoice->deliveryHeader->saleHeader->date)) . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function instantiate($id) {
        if (empty($id))
            $saleInvoice = new SaleInvoiceTransaction(new SaleInvoice());
        else {
            $saleInvoice = $this->loadModel($id);
            $saleInvoice = new SaleInvoiceTransaction($saleInvoice);
        }

        return $saleInvoice;
    }

    public function loadModel($id) {
        $model = SaleInvoice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($saleInvoice) {
        if (isset($_POST['SaleInvoice'])) {
            $saleInvoice->header->attributes = $_POST['SaleInvoice'];
        }
    }

}
