<?php

class PurchaseReceiptController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view'
                || $filterChain->action->id === 'create'
                || $filterChain->action->id === 'ajaxHtmlAddReceive'
                || $filterChain->action->id === 'ajaxHtmlRemoveDetail'
                || $filterChain->action->id === 'ajaxJsonCodeNumber'
                || $filterChain->action->id === 'ajaxJsonSupplier'
                || $filterChain->action->id === 'ajaxHtmlResetDetail'
                || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('purchaseReceiptCreate') || Yii::app()->user->checkAccess('purchaseReceiptEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'admin'
                || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('purchaseReceiptEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function instantiate($id) {
        if (empty($id))
            $purchaseReceipt = new PurchaseReceipt(new PurchaseReceiptHeader(), array());
        else {
            $purchaseReceiptHeader = $this->loadModel($id);
            $purchaseReceipt = new PurchaseReceipt($purchaseReceiptHeader, $purchaseReceiptHeader->purchaseReceiptDetails);
        }

        return $purchaseReceipt;
    }

    public function loadModel($id) {
        $model = PurchaseReceiptHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState($purchaseReceipt) {
        if (isset($_POST['PurchaseReceiptHeader'])) {
            $purchaseReceipt->header->attributes = $_POST['PurchaseReceiptHeader'];
        }

        if (isset($_POST['PurchaseReceiptDetail'])) {
            foreach ($_POST['PurchaseReceiptDetail'] as $i => $item) {
                if (isset($purchaseReceipt->details[$i]))
                    $purchaseReceipt->details[$i]->attributes = $item;
                else {
                    $detail = new PurchaseReceiptDetail();
                    $detail->attributes = $item;
                    $purchaseReceipt->details[] = $detail;
                }
            }
            if (count($_POST['PurchaseReceiptDetail']) < count($purchaseReceipt->details))
                array_splice($purchaseReceipt->details, $i + 1);
        }
        else
            $purchaseReceipt->details = array();
    }

    public function actionCreate() {
        $purchaseReceipt = $this->instantiate(null);
        $purchaseReceipt->header->admin_id = Yii::app()->user->id;

        //this variable is for filtering receive header that shown in pop up
        $branchId = isset($_GET['PurchaseReceiptHeader']['branch_id']) ? $_GET['PurchaseReceiptHeader']['branch_id'] : '';
        $supplierId = isset($_GET['PurchaseReceiptHeader']['supplier_id']) ? $_GET['PurchaseReceiptHeader']['supplier_id'] : '';

        $supplier = Search::bind(new Supplier('search'), isset($_GET['Supplier']) ? $_GET['Supplier'] : array());
        $supplierDataProvider = $supplier->search();
        $supplierDataProvider->criteria->with = array(
            'account:resetScope',
        );

        $receive = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());

        $receiveDataProvider = $receive->searchByPurchaseReceipt();
        $receiveDataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => array(
                    'supplier:resetScope'
                ),
            ),
        );

        $receiveDataProvider->criteria->addCondition("supplier.id = :supplier_id");
        $receiveDataProvider->criteria->params[':supplier_id'] = $supplierId;

        if (!empty($branchId)) {
            $supplierDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $supplierDataProvider->criteria->params[':branch_id'] = $branchId;

            $receiveDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $receiveDataProvider->criteria->params[':branch_id'] = $branchId;
        }

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseReceipt);
            $purchaseReceipt->generateCodeNumber($purchaseReceipt->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseReceipt->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseReceipt->header->date)));

            if ($purchaseReceipt->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseReceiptMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseReceipt->header->id));
            }
        }

        $this->render('create', array(
            'purchaseReceipt' => $purchaseReceipt,
            'receive' => $receive,
            'supplier' => $supplier,
            'receiveDataProvider' => $receiveDataProvider,
            'supplierDataProvider' => $supplierDataProvider,
        ));
    }

    public function actionUpdate($id) {
        $purchaseReceipt = $this->instantiate($id);
        $purchaseReceipt->header->admin_id = Yii::app()->user->id;

        $supplier = Search::bind(new Supplier('search'), isset($_GET['Supplier']) ? $_GET['Supplier'] : array());
        $supplierDataProvider = $supplier->search();

        $receive = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $receive->normalizeCnMonthBy($cnMonth);

        $receiveDataProvider = $receive->search();
        $receiveDataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => array('supplier:resetScope'))
        );

        $supplierCompany = isset($_GET['SupplierCompany']) ? $_GET['SupplierCompany'] : '';
        $receiveDataProvider->criteria->addCondition("supplier.company LIKE :company");
        $receiveDataProvider->criteria->params[':company'] = "%{$supplierCompany}%";

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseReceipt);

            if ($purchaseReceipt->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseReceiptMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseReceipt->header->id));
            }
        }
        $this->render('update', array(
            'purchaseReceipt' => $purchaseReceipt,
            'receive' => $receive,
            'supplier' => $supplier,
            'cnMonth' => strtoupper($cnMonth),
            'receiveDataProvider' => $receiveDataProvider,
            'supplierDataProvider' => $supplierDataProvider,
            'supplierCompany' => $supplierCompany
        ));
    }

    public function actionView($id) {
        $purchaseReceipt = $this->loadModel($id);

        $supplier = $purchaseReceipt->supplier(array('scopes' => 'resetScope'));
        $branch = $purchaseReceipt->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('purchase_receipt_header_id', $purchaseReceipt->id);
        $detailsDataProvider = new CActiveDataProvider('PurchaseReceiptDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'receiveHeader:resetScope' => array(
                'with' => array('purchaseHeader:resetScope' => array(
                        'with' => array('supplier:resetScope')
                ))
                ));

        $this->render('view', array(
            'purchaseReceipt' => $purchaseReceipt,
            'supplier' => $supplier,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['PurchaseReceiptMemoAllowed']) && Yii::app()->session['PurchaseReceiptMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('PurchaseReceiptMemoAllowed');
        $purchaseReceipt = $this->loadModel($id);

        $this->memoToExcel($purchaseReceipt);
    }

    protected function memoToExcel($purchaseReceipt) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Purchase Receipt');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.5);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Receipt');

        $worksheet->getColumnDimension('A')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setWidth('15');
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setWidth('1');
        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('20');
        $worksheet->getColumnDimension('D')->setAutoSize(false);
        $worksheet->getColumnDimension('D')->setWidth('16');
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setWidth('16');
        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('22');

        $counter = 1;
        if ($purchaseReceipt->branch_id != 4) {
            $worksheet->mergeCells("A{$counter}:F{$counter}");
            $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("A{$counter}", CHtml::value($purchaseReceipt, 'branch.name'));
            $counter++;
        }

        $worksheet->mergeCells("A{$counter}:F{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'TANDA TERIMA PEMBELIAN');
        $counter++;
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Tanda Terima No.');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->setCellValue("C{$counter}", $purchaseReceipt->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT));
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Tanggal');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchaseReceipt, 'date'));
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Supplier');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->mergeCells("C{$counter}:D{$counter}");
        $worksheet->setCellValue("C{$counter}", CHtml::value($purchaseReceipt, 'supplier.company'));
        $counter++;

        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        $worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'Tgl. Invoice');
        $worksheet->mergeCells("B{$counter}:C{$counter}");
        $worksheet->setCellValue("B{$counter}", 'No. Invoice');
        $worksheet->setCellValue("D{$counter}", 'No. SJ');
        $worksheet->setCellValue("E{$counter}", 'Jumlah (Rp)');
        $worksheet->setCellValue("F{$counter}", 'Memo');
        $counter++;

//        $worksheet->mergeCells("A{$counter}:F{$counter}");
//        $counter += 2;

        foreach ($purchaseReceipt->purchaseReceiptDetails as $i => $detail) {
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->setCellValue("A{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime(CHtml::value($detail, 'receiveHeader.date'))));
            $worksheet->mergeCells("B{$counter}:C{$counter}");
            $worksheet->setCellValue("B{$counter}", $detail->receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT));
            $worksheet->setCellValue("D{$counter}", $detail->receiveHeader->reference);
            $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("E{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'receiveHeader.totalPurchase')));
            $worksheet->setCellValue("F{$counter}", $detail->memo);
            $counter++;
        }

        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--):
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;

        endfor;

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("A{$counter}:F{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:F{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->mergeCells("A{$counter}:C{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("A{$counter}", 'TOTAL');
        $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("E{$counter}", CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($purchaseReceipt, 'totalReceivePrice')))));
        $counter++;

        $worksheet->mergeCells("A{$counter}:F{$counter}");
        $objPHPExcel->getActiveSheet()
        ->getStyle("A{$counter}:F{$counter}")
        ->getAlignment()
        ->setWrapText(true);
                
        $worksheet->setCellValue("A{$counter}", 'Terbilang :' . CHtml::encode(NumberWord::numberName(floor(CHtml::value($purchaseReceipt, 'totalReceivePrice')))) . 'rupiah');
        $counter++;
        $counter++;

        $worksheet->setCellValue("F{$counter}", 'Hormat Kami,');
        $counter++;
        $counter++;
        $counter++;
        $counter++;
        $counter++;
        $worksheet->setCellValue("F{$counter}", '('.CHtml::encode(CHtml::value($purchaseReceipt, 'admin.username')).')');

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="receipt.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionAdmin() {
        $purchaseReceipt = Search::bind(new PurchaseReceiptHeader('search'), isset($_GET['PurchaseReceiptHeader']) ? $_GET['PurchaseReceiptHeader'] : array());

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $purchaseReceiptDataProvider = $purchaseReceipt->resetScope()->searchWithPaging();
        $purchaseReceiptDataProvider->criteria->with = array(
            'purchaseReceiptDetails:resetScope' => array(
                'with' => array('receiveHeader:resetScope' => array(
                        'with' => array('purchaseHeader:resetScope' => array(
                                'with' => 'supplier:resetScope'
                        ))
                ))
                ));

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $purchaseReceiptDataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'purchaseReceipt' => $purchaseReceipt,
            'purchaseReceiptDataProvider' => $purchaseReceiptDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $purchaseReceiptHeader = $this->loadModel($id);

            JournalAccounting::model()->deleteAllByAttributes(array(
                'transaction_number' => $purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT), 
                'branch_id' => $purchaseReceiptHeader->branch_id, 
                'type' => 5,
            ));

            if ($purchaseReceiptHeader !== null) {
                $dbTransaction = Yii::app()->db->beginTransaction();
                try {
                    $valid = TRUE;
                    
                    foreach ($purchaseReceiptHeader->purchaseReceiptDetails as $purchaseReceiptDetail) {
                        $purchaseReceiptDetail->is_inactive = ActiveRecord::INACTIVE;
                        $valid = $valid && $purchaseReceiptDetail->update(array('is_inactive'));
                    }

                    $purchaseReceiptHeader->is_inactive = ActiveRecord::INACTIVE;
                    $valid = $valid && $purchaseReceiptHeader->update(array('is_inactive'));

                    $valid = $valid && JournalAccounting::model()->deleteAllByAttributes(array(
                        'transaction_number' => $purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT),
                        'branch_id' => $purchaseReceiptHeader->branch_id
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
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxJsonSupplier($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);
            $purchaseReceiptSupplier = $purchaseReceipt->header->supplier(array('scopes' => 'resetScope'));

            $object = array(
                'supplier_id' => $purchaseReceiptSupplier->company,
                'supplier_name' => $purchaseReceiptSupplier->name,
                'supplier_address' => $purchaseReceiptSupplier->address,
            );
            
            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlResetDetail($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);

            if (isset($_POST['PurchaseReceiptHeader']['supplier_id']))
                $purchaseReceipt->resetDetail($_POST['PurchaseReceiptHeader']['supplier_id']);

            $this->renderPartial('_detail', array(
                'purchaseReceipt' => $purchaseReceipt,
            ));
        }
    }

    public function actionAjaxJsonTotal($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);

            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchaseReceipt->details[$index], 'total')));

            echo CJSON::encode(array(
                'total' => $total,
            ));
        }
    }

    public function actionAjaxHtmlRemoveDetail($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);

            $purchaseReceipt->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'purchaseReceipt' => $purchaseReceipt,
            ));
        }
    }

    public function actionAjaxHtmlAddReceive($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);

            if (isset($_POST['ReceiveId']))
                $purchaseReceipt->addDetail($_POST['ReceiveId']);

            $this->renderPartial('_detail', array(
                'purchaseReceipt' => $purchaseReceipt,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReceipt = $this->instantiate($id);
            $this->loadState($purchaseReceipt);

            $purchaseReceipt->generateCodeNumber($purchaseReceipt->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseReceipt->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseReceipt->header->date)));
            $codeNumber = CHtml::encode($purchaseReceipt->header->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }
}