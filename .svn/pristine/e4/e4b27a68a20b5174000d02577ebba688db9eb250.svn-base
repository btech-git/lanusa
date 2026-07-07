<?php

class SalePaymentController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create'
                || $filterChain->action->id === 'ajaxJsonCodeNumber'
                || $filterChain->action->id === 'ajaxJsonReceipt'
                || $filterChain->action->id === 'ajaxHtmlResetDetail'
                || $filterChain->action->id === 'ajaxHtmlAddAccount'
                || $filterChain->action->id === 'ajaxJsonSummary'
                || $filterChain->action->id === 'ajaxHtmlRemovePayment'
                || $filterChain->action->id === 'view') {
            if (!(Yii::app()->user->checkAccess('salePaymentCreate') || Yii::app()->user->checkAccess('salePaymentEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('salePaymentEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $salePayment = $this->instantiate(null);
        $salePayment->header->admin_id = Yii::app()->user->id;

        $branchId = isset($_GET['SalePaymentHeader']['branch_id']) ? $_GET['SalePaymentHeader']['branch_id'] : '';

        $saleReceiptHeader = Search::bind(new SaleReceiptHeader('search'), isset($_GET['SaleReceiptHeader']) ? $_GET['SaleReceiptHeader'] : array());
        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $saleReceiptHeader->normalizeCnMonthBy($cnMonth);

        $saleReceiptDataProvider = $saleReceiptHeader->searchBySalePayment();
        $saleReceiptDataProvider->criteria->with = array(
            'customer:resetScope',
        );

        $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
        $accountDataProvider = $account->search();
        $accountDataProvider->criteria->with = array(
            'accountCategory:resetScope',
        );

        if (!empty($branchId)) {
            $saleReceiptDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $saleReceiptDataProvider->criteria->params[':branch_id'] = $branchId;

            $accountDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $accountDataProvider->criteria->params[':branch_id'] = $branchId;
        }

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($salePayment);
            $salePayment->header->branch_id = ($salePayment->header->saleReceiptHeader === null) ? '' : $salePayment->header->saleReceiptHeader->branch_id;
            $salePayment->generateCodeNumber($salePayment->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($salePayment->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($salePayment->header->date)));

            if ($salePayment->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $salePayment->header->id));
        }

        $this->render('create', array(
            'salePayment' => $salePayment,
            'saleReceiptHeader' => $saleReceiptHeader,
            'account' => $account,
            'cnMonth' => strtoupper($cnMonth),
            'saleReceiptDataProvider' => $saleReceiptDataProvider,
            'accountDataProvider' => $accountDataProvider,
        ));
    }

    public function actionUpdate($id) {
        $salePayment = $this->instantiate($id);
        $salePayment->header->admin_id = Yii::app()->user->id;

        $saleReceiptHeader = Search::bind(new SaleReceiptHeader('search'), isset($_GET['SaleReceiptHeader']) ? $_GET['SaleReceiptHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $saleReceiptHeader->normalizeCnMonthBy($cnMonth);

        $saleReceiptDataProvider = $saleReceiptHeader->searchBySalePayment();
        $saleReceiptDataProvider->criteria->with = array(
            'customer:resetScope',
        );

        $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
        $accountDataProvider = $account->search();
        $accountDataProvider->criteria->with = array(
            'accountCategory:resetScope',
        );

        $branchId = $salePayment->header->branch_id;

        if (!empty($branchId)) {
            $saleReceiptDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $saleReceiptDataProvider->criteria->params[':branch_id'] = $branchId;

            $accountDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $accountDataProvider->criteria->params[':branch_id'] = $branchId;
        }

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($salePayment);

            if ($salePayment->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $salePayment->header->id));
        }

        $this->render('update', array(
            'salePayment' => $salePayment,
            'saleReceiptHeader' => $saleReceiptHeader,
            'account' => $account,
            'cnMonth' => strtoupper($cnMonth),
            'saleReceiptDataProvider' => $saleReceiptDataProvider,
            'accountDataProvider' => $accountDataProvider,
        ));
    }

    public function actionView($id) {
        $salePayment = $this->loadModel($id);

        $saleReceiptHeader = $salePayment->saleReceiptHeader(array(
            'scopes' => 'resetScope',
            'with' => 'customer:resetScope',
                ));
        $branch = $salePayment->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_payment_header_id', $salePayment->id);
        $detailsDataProvider = new CActiveDataProvider('SalePaymentDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'paymentType:resetScope',
            'account:resetScope',
        );

        $this->render('view', array(
            'salePayment' => $salePayment,
            'saleReceiptHeader' => $saleReceiptHeader,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $salePayment = SalePaymentHeader::model()->resetScope()->findByPk($id);
            if ($salePayment !== null) {
                $dbTransaction = Yii::app()->db->beginTransaction();
                try {
                    $valid = TRUE;
                    foreach ($salePayment->salePaymentDetails as $salePaymentDetail) {
                        $salePaymentDetail->is_inactive = ActiveRecord::INACTIVE;
                        $valid = $valid && $salePaymentDetail->update(array('is_inactive'));
                    }

                    $salePayment->is_inactive = ActiveRecord::INACTIVE;
                    $valid = $valid && $salePayment->update(array('is_inactive'));

                    $valid = $valid && JournalAccounting::model()->deleteAllByAttributes(array(
                                'transaction_number' => $salePayment->getCodeNumber(SalePaymentHeader::CN_CONSTANT),
                                'branch_id' => $salePayment->branch_id
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

    public function actionAdmin() {
        $salePayment = Search::bind(new SalePaymentHeader('search'), isset($_GET['SalePaymentHeader']) ? $_GET['SalePaymentHeader'] : array());

        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $salePayment->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'saleReceiptHeader:resetScope' => array(
                'with' => 'customer:resetScope',
            ),
        );
        $dataProvider->criteria->compare('customer_id', $customerId);

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'salePayment' => $salePayment,
            'dataProvider' => $dataProvider,
            'customerId' => $customerId,
        ));
    }

    public function actionMemo($id) {

        $salePayment = $this->loadModel($id);

        $this->memoToExcel($salePayment);
    }

    protected function memoToExcel($salePayment) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Official Receipt');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.5);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Official Receipt');

        $worksheet->getColumnDimension('A')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setWidth('16');
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setWidth('1');
        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('1');
        $worksheet->getColumnDimension('D')->setAutoSize(false);
        $worksheet->getColumnDimension('D')->setWidth('20');
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setWidth('6');
        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('14');
        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('1');
        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('H')->setWidth('30');

        $counter = 1;
        if ($salePayment->branch_id != 4) {
            $worksheet->mergeCells("A{$counter}:H{$counter}");
            $worksheet->getStyle("A{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("A{$counter}", CHtml::value($salePayment, 'branch.name'));
            $counter++;
        }

        $worksheet->mergeCells("A{$counter}:H{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'Official Receipt');
        $counter++;
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Pelunasan No.');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->mergeCells("C{$counter}:D{$counter}");
        $worksheet->setCellValue("C{$counter}", $salePayment->getCodeNumber(SalePaymentHeader::CN_CONSTANT));

        $worksheet->setCellValue("F{$counter}", 'Customer');
        $worksheet->setCellValue("G{$counter}", ':');
        $worksheet->setCellValue("H{$counter}", CHtml::value($salePayment, 'saleReceiptHeader.customer.company'));
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Tanggal');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->mergeCells("C{$counter}:D{$counter}");
        $worksheet->setCellValue("C{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($salePayment, 'date'))));
        $worksheet->setCellValue("F{$counter}", 'Tanda Terima No.');
        $worksheet->setCellValue("G{$counter}", ':');
        $worksheet->setCellValue("H{$counter}", $salePayment->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT));
        $counter++;
        $counter++;

        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("A{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
        $worksheet->mergeCells("A{$counter}:C{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Account');
        $worksheet->setCellValue("D{$counter}", 'Jenis Pembayaran');
        $worksheet->mergeCells("E{$counter}:F{$counter}");
        $worksheet->setCellValue("E{$counter}", 'Jumlah (Rp)');
        $worksheet->mergeCells("G{$counter}:H{$counter}");
        $worksheet->setCellValue("G{$counter}", 'Memo');
        $counter++;

        foreach ($salePayment->salePaymentDetails as $i => $detail) {

            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->mergeCells("A{$counter}:C{$counter}");
            $worksheet->setCellValue("A{$counter}", CHtml::value($detail, 'account.name'));
            $worksheet->setCellValue("D{$counter}", CHtml::value($detail, 'paymentType.name'));

            $worksheet->mergeCells("E{$counter}:F{$counter}");
            $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("E{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'amount')));
            $worksheet->mergeCells("G{$counter}:H{$counter}");
            $worksheet->setCellValue("G{$counter}", $detail->memo);

            $counter++;
        }

        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--):
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;

        endfor;

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->mergeCells("A{$counter}:D{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("A{$counter}", 'TOTAL');
        $worksheet->mergeCells("E{$counter}:F{$counter}");
        $worksheet->getStyle("E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("E{$counter}", CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($salePayment, 'totalSale')))));
        $counter++;

        $worksheet->mergeCells("A{$counter}:H{$counter}");
        $objPHPExcel->getActiveSheet()
                ->getStyle("A{$counter}:H{$counter}")
                ->getAlignment()
                ->setWrapText(true);
        $worksheet->setCellValue("A{$counter}", 'Terbilang :' . CHtml::encode(NumberWord::numberName(floor(CHtml::value($salePayment, 'totalSale')))) . 'rupiah');
        $counter++;
        $counter++;

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);

        $worksheet->mergeCells("A{$counter}:C{$counter}");
        $worksheet->setCellValue("A{$counter}", 'Dibuat,');
        $worksheet->setCellValue("D{$counter}", 'Diperiksa,');
        $worksheet->mergeCells("E{$counter}:F{$counter}");
        $worksheet->setCellValue("E{$counter}", 'Disetujui,');
        $worksheet->mergeCells("G{$counter}:H{$counter}");
        $worksheet->setCellValue("G{$counter}", 'Diterima,');
        $counter++;

        for ($x = 0; $x < 3; $x++):
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;
        endfor;

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("E{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:H{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $counter++;

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Pembayaran Penjualan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->amountPaid;

        return $grandTotal;
    }

    public function actionAjaxJsonReceipt($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $salePayment = $this->instantiate($id);
            $this->loadState($salePayment);

            if (!isset($_POST['SalePaymentDetail']))
                $salePayment->details = array();

            $salePaymentReceipt = $salePayment->header->saleReceiptHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'customer:resetScope',
                ),
                    ));

            $salePayment->generateCodeNumber($salePaymentReceipt->branch_id, date('m'), date('y'));

            $object = array(
                'sale_receipt_header_codeNumber' => $salePaymentReceipt->getCodeNumber(SaleReceiptHeader::CN_CONSTANT),
                'sale_payment_header_codeNumber' => $salePayment->header->getCodeNumber(SalePaymentHeader::CN_CONSTANT),
                'sale_receipt_header_date' => Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($salePaymentReceipt->date)),
                'sale_receipt_header_customer' => $salePaymentReceipt->customer->company,
                'branch' => $salePaymentReceipt->branch->name,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlAddAccount($id) {
        if (Yii::app()->request->isAjaxRequest) {

            $salePayment = $this->instantiate($id);

            $this->loadState($salePayment);

            if (isset($_POST['AccountId']))
                $salePayment->addDetail($_POST['AccountId']);

            $this->renderPartial('_detail', array(
                'salePayment' => $salePayment,
            ));
        }
    }

    public function actionAjaxJsonSummary($index, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $salePayment = $this->instantiate($id);

            $this->loadState($salePayment);

            $amount = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($salePayment->details[$index], 'amount')));
            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($salePayment, 'totalInvoice')));
            $payment = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($salePayment, 'payment')));
            $remaining = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($salePayment, 'remaining')));
            $total_payment = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($salePayment, 'totalPayment')));

            echo CJSON::encode(array(
                'amount' => $amount,
                'total' => $total,
                'payment' => $payment,
                'remaining' => $remaining,
                'total_payment' => $total_payment,
            ));
        }
    }

    public function actionAjaxHtmlRemovePayment($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $salePayment = $this->instantiate($id);

            $this->loadState($salePayment);

            $salePayment->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'salePayment' => $salePayment,
            ));
        }
    }

    public function actionAjaxHtmlResetDetail($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $salePayment = $this->instantiate($id);
            $this->loadState($salePayment);

            $salePayment->details = array();         //reset detail

            $this->renderPartial('_detail', array(
                'salePayment' => $salePayment,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $salePayment = $this->instantiate($id);
            $this->loadState($salePayment);

            $salePayment->generateCodeNumber($salePayment->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($salePayment->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($salePayment->header->date)));
            $codeNumber = CHtml::encode($salePayment->header->getCodeNumber(SalePaymentHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $salePayment = new SalePayment(new SalePaymentHeader(), array());
        else {
            $salePaymentHeader = $this->loadModel($id);
            $salePayment = new SalePayment($salePaymentHeader, $salePaymentHeader->salePaymentDetails);
        }

        return $salePayment;
    }

    public function loadModel($id) {
        $model = SalePaymentHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($salePayment) {
        if (isset($_POST['SalePaymentHeader'])) {
            $salePayment->header->attributes = $_POST['SalePaymentHeader'];
        }
        if (isset($_POST['SalePaymentDetail'])) {
            foreach ($_POST['SalePaymentDetail'] as $i => $item) {

                if (isset($salePayment->details[$i]))
                    $salePayment->details[$i]->attributes = $item;
                else {
                    $detail = new SalePaymentDetail();
                    $detail->attributes = $item;
                    $salePayment->details[] = $detail;
                }
            }
            if (count($_POST['SalePaymentDetail']) < count($salePayment->details))
                array_splice($salePayment->details, $i + 1);
        }
        else
            $salePayment->details = array();
    }

}
