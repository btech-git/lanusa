<?php

class SaleChequeController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view'
                || $filterChain->action->id === 'create'
                || $filterChain->action->id === 'ajaxHtmlAddSaleReceipt'
                || $filterChain->action->id === 'ajaxJsonAmount'
                || $filterChain->action->id === 'ajaxHtmlRemoveDetail'
                || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('saleChequeCreate') || Yii::app()->user->checkAccess('saleChequeEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('saleChequeEdit')))
                $this->redirect(array('/site/login'));
        }


        $filterChain->run();
    }

    public function instantiate($id) {
        if (empty($id))
            $saleCheque = new SaleChequeTransaction(new SaleChequeHeader(), array());
        else {
            $saleCheque = $this->loadModel($id);
            $saleCheque = new SaleChequeTransaction($saleCheque, $saleCheque->saleChequeDetails);
        }

        return $saleCheque;
    }

    public function loadModel($id) {
        $model = SaleChequeHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState(&$saleCheque) {
        if (isset($_POST['SaleChequeHeader'])) {
            $saleCheque->header->attributes = $_POST['SaleChequeHeader'];
        }

        if (isset($_POST['SaleChequeDetail'])) {
            foreach ($_POST['SaleChequeDetail'] as $i => $item) {
                if (isset($saleCheque->details[$i]))
                    $saleCheque->details[$i]->attributes = $item;
                else {
                    $detail = new SaleChequeDetail();
                    $detail->attributes = $item;
                    $saleCheque->details[] = $detail;
                }
            }
            if (count($_POST['SaleChequeDetail']) < count($saleCheque->details))
                array_splice($saleCheque->details, $i + 1);
        }
        else
            $saleCheque->details = array();
    }

    public function actionCreate() {
        $saleCheque = $this->instantiate(null);
        $saleCheque->header->admin_id = Yii::app()->user->id;

        $branchId = isset($_GET['SaleChequeHeader']['branch_id']) ? $_GET['SaleChequeHeader']['branch_id'] : '';
        $customerId = isset($_GET['SaleChequeHeader']['customer_id']) ? $_GET['SaleChequeHeader']['customer_id'] : '';

        $saleReceiptHeader = Search::bind(new SaleReceiptHeader('search'), isset($_GET['SaleReceiptHeader']) ? $_GET['SaleReceiptHeader'] : array());

        $saleReceiptHeaderDataProvider = $saleReceiptHeader->searchByReceipt();
        $saleReceiptHeaderDataProvider->criteria->with = array(
            'customer:resetScope',
        );
        $error = false;

        $customer = Search::bind(new Customer('search'), isset($_GET['Customer']) ? $_GET['Customer'] : array());
        $customerDataProvider = $customer->search();

        if (!empty($branchId)) {
            $customerDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $customerDataProvider->criteria->params[':branch_id'] = $branchId;

            $saleReceiptHeaderDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $saleReceiptHeaderDataProvider->criteria->params[':branch_id'] = $branchId;
        }

        if (!empty($customerId)) {
            $saleReceiptHeaderDataProvider->criteria->addCondition("t.customer_id = :customer_id");
            $saleReceiptHeaderDataProvider->criteria->params[':customer_id'] = $customerId;
        }


        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleCheque);
            $saleCheque->generateCodeNumber($saleCheque->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleCheque->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleCheque->header->date)));

            if ($saleCheque->save(Yii::app()->db)) {
                Yii::app()->session['SaleChequeMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleCheque->header->id));
            }
            else
                $error = true;
        }

        $this->render('create', array(
            'saleCheque' => $saleCheque,
            'saleReceiptHeader' => $saleReceiptHeader,
//			'cnMonth' => strtoupper($cnMonth),
            'saleReceiptHeaderDataProvider' => $saleReceiptHeaderDataProvider,
            'error' => $error,
            'customerId' => $customerId,
            'customer' => $customer,
            'customerDataProvider' => $customerDataProvider
        ));
    }

    public function actionUpdate($id) {
        $saleCheque = $this->instantiate($id);
        $saleCheque->header->admin_id = Yii::app()->user->id;
        $saleReceiptHeader = Search::bind(new SaleReceiptHeader('search'), isset($_GET['SaleReceiptHeader']) ? $_GET['SaleReceiptHeader'] : array());

        $saleReceiptHeaderDataProvider = $saleReceiptHeader->searchByReceipt();
        $saleReceiptHeaderDataProvider->criteria->with = array(
            'customer:resetScope',
        );

        $customer = Search::bind(new Customer('search'), isset($_GET['Customer']) ? $_GET['Customer'] : array());
        $customerDataProvider = $customer->search();

        $error = false;

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleCheque);

            if ($saleCheque->save(Yii::app()->db)) {
                Yii::app()->session['SaleChequeMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleCheque->header->id));
            }
            else
                $error = true;
        }

        $this->render('update', array(
            'saleCheque' => $saleCheque,
            'saleReceiptHeader' => $saleReceiptHeader,
//			'cnMonth' => strtoupper($cnMonth),
            'saleReceiptHeaderDataProvider' => $saleReceiptHeaderDataProvider,
            'error' => $error,
            'customerDataProvider' => $customerDataProvider,
            'customer' => $customer,
        ));
    }

    public function actionView($id) {
        $saleCheque = $this->loadModel($id);

        $criteria = new CDbCriteria;
        $criteria->compare('sale_cheque_header_id', $saleCheque->id);
        $detailsDataProvider = new CActiveDataProvider('SaleChequeDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'saleReceiptHeader:resetScope' => array(
                'with' => 'customer:resetScope'
            ),
        );

        $branch = $saleCheque->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_cheque_id', $saleCheque->id);


        $this->render('view', array(
            'saleCheque' => $saleCheque,
            'detailsDataProvider' => $detailsDataProvider,
            'branch' => $branch,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['SaleChequeMemoAllowed']) && Yii::app()->session['SaleChequeMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('SaleChequeMemoAllowed');

        //$saleCheque = SaleCheque::model()->findByPk($id);
        $saleCheque = $this->loadModel($id);

        $saleReceiptHeader = new SaleReceiptHeader();

        foreach ($saleCheque->saleChequeDetails as $details) {
            $saleReceiptHeader = $details->saleReceiptHeader;
        }



//		$saleReceiptHeader = $saleCheque->saleChequeDetails->saleReceiptHeader(array(
//			'scopes' => 'resetScope', 
//			'with'=>'customer:resetScope',
//		));

        $branch = $saleCheque->branch(array('scopes' => 'resetScope'));

        $this->memoToExcel($saleCheque);

//		$saleChequeCustomer = ($saleCheque->is_non_tax) ? $saleCheque->saleReceiptHeader->customer->name : $saleCheque->saleReceiptHeader->customer->company;
//		$saleChequeHeaderText = ($saleCheque->is_non_tax) ? '' : 'PT. Lanusa';
//		$this->render('memo', array(
//			'saleCheque' => $saleCheque,
//			'saleReceiptHeader'=>$saleReceiptHeader,
//			'branch'=>$branch,
////			'saleChequeCustomer' => $saleChequeCustomer,
////			'saleChequeHeaderText' => $saleChequeHeaderText,
//		));
    }

    protected function memoToExcel($saleCheque) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Penerimaan Giro');

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.5);

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Penerimaan Giro');

        $worksheet->getColumnDimension('A')->setAutoSize(false);
        $worksheet->getColumnDimension('A')->setWidth('15');
        $worksheet->getColumnDimension('B')->setAutoSize(false);
        $worksheet->getColumnDimension('B')->setWidth('1');
        $worksheet->getColumnDimension('C')->setAutoSize(false);
        $worksheet->getColumnDimension('C')->setWidth('18');
        $worksheet->getColumnDimension('D')->setAutoSize(false);
        $worksheet->getColumnDimension('D')->setWidth('4');
        $worksheet->getColumnDimension('E')->setAutoSize(false);
        $worksheet->getColumnDimension('E')->setWidth('12');
        $worksheet->getColumnDimension('F')->setAutoSize(false);
        $worksheet->getColumnDimension('F')->setWidth('1');
        $worksheet->getColumnDimension('G')->setAutoSize(false);
        $worksheet->getColumnDimension('G')->setWidth('12');
        $worksheet->getColumnDimension('H')->setAutoSize(false);
        $worksheet->getColumnDimension('H')->setWidth('14');
        $worksheet->getColumnDimension('I')->setAutoSize(false);
        $worksheet->getColumnDimension('I')->setWidth('14');

        $counter = 1;
        if ($saleCheque->branch_id != 4) {
            $worksheet->mergeCells("A{$counter}:I{$counter}");
            $worksheet->getStyle("A{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("A{$counter}:I{$counter}")->getFont()->setBold(true);
            $worksheet->setCellValue("A{$counter}", CHtml::value($saleCheque, 'branch.name'));
            $counter++;
        }

        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'Penerimaan Giro');
        $counter++;
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'No. Nota Giro');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->setCellValue("C{$counter}", $saleCheque->getCodeNumber(SaleChequeHeader::CN_CONSTANT));

        $worksheet->setCellValue("E{$counter}", 'Customer');
        $worksheet->setCellValue("F{$counter}", ':');
        $worksheet->mergeCells("G{$counter}:I{$counter}");
        $worksheet->setCellValue("G{$counter}", CHtml::value($saleCheque, 'customer.company'));
        $counter++;

        $worksheet->setCellValue("A{$counter}", 'Tgl. Terima Giro');
        $worksheet->setCellValue("B{$counter}", ':');
        $worksheet->setCellValue("C{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleCheque, 'receive_date'))));
        $worksheet->setCellValue("E{$counter}", 'Tgl. Giro Cair');
        $worksheet->setCellValue("F{$counter}", ':');
        $worksheet->mergeCells("G{$counter}:I{$counter}");
        $worksheet->setCellValue("G{$counter}", Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleCheque, 'due_date'))));
        $counter++;
        $counter++;

        $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        $worksheet->getStyle("A{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A{$counter}:I{$counter}")->getFont()->setBold(true);
        $worksheet->setCellValue("A{$counter}", 'Tanggal');
        $worksheet->mergeCells("B{$counter}:C{$counter}");
        $worksheet->setCellValue("B{$counter}", 'No. Tanda Terima');
        $worksheet->mergeCells("D{$counter}:E{$counter}");
        $worksheet->setCellValue("D{$counter}", 'Jatuh Tempo');
        $worksheet->mergeCells("F{$counter}:G{$counter}");
        $worksheet->setCellValue("F{$counter}", 'Jumlah (Rp)');
        $worksheet->setCellValue("H{$counter}", 'Bank');
        $worksheet->setCellValue("I{$counter}", 'No. Cek / Giro');
        $counter++;

        foreach ($saleCheque->saleChequeDetails as $i => $detail) {

            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $worksheet->setCellValue("A{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.date'))));
            $worksheet->mergeCells("B{$counter}:C{$counter}");
            $worksheet->setCellValue("B{$counter}", $detail->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT));

            $worksheet->mergeCells("D{$counter}:E{$counter}");
            $worksheet->getStyle("D{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $worksheet->setCellValue("D{$counter}", Yii::app()->dateFormatter->format('d MMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.due_date'))));

            $worksheet->mergeCells("F{$counter}:G{$counter}");
            $worksheet->getStyle("F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("F{$counter}", Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'amount')));

            $worksheet->getStyle("H{$counter}:I{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $worksheet->setCellValue("H{$counter}", $detail->bank);
            $worksheet->setCellValue("I{$counter}", $detail->cheque_number);

            $counter++;
        }

        for ($j = 8, $i = $i % $j + 1; $j > $i; $j--):
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("B{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;

        endfor;

        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("F{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("H{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $worksheet->getStyle("A{$counter}:I{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:I{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->mergeCells("A{$counter}:E{$counter}");
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("A{$counter}", 'TOTAL');
        $worksheet->mergeCells("F{$counter}:G{$counter}");
        $worksheet->getStyle("F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("F{$counter}", CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleCheque, 'totalAmount')))));
        $counter++;

        $worksheet->mergeCells("A{$counter}:I{$counter}");
        $objPHPExcel->getActiveSheet()
                ->getStyle("A{$counter}:I{$counter}")
                ->getAlignment()
                ->setWrapText(true);
        $worksheet->setCellValue("A{$counter}", 'Terbilang :' . CHtml::encode(NumberWord::numberName(floor(CHtml::value($saleCheque, 'totalAmount')))) . 'rupiah');
        $counter++;
        $counter++;

        $worksheet->mergeCells("A{$counter}:C{$counter}");
        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->mergeCells("G{$counter}:I{$counter}");
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}:I{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}:I{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        $worksheet->getStyle("A{$counter}:H{$counter}")->getFont()->setBold(true);
        $worksheet->getStyle("A{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("G{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->setCellValue("A{$counter}", 'Diterima,');
        $worksheet->setCellValue("G{$counter}", 'Diperiksa,');
        $counter++;

        for ($x = 0; $x < 4; $x++):
            $worksheet->mergeCells("A{$counter}:C{$counter}");
            $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->mergeCells("G{$counter}:I{$counter}");
            $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $counter++;
        endfor;

        $worksheet->mergeCells("A{$counter}:C{$counter}");
        $worksheet->getStyle("A{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("D{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("A{$counter}:C{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->mergeCells("G{$counter}:I{$counter}");
        $worksheet->getStyle("G{$counter}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("I{$counter}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle("G{$counter}:I{$counter}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $counter++;


        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Penerimaan Giro.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $saleCheque = $this->loadModel($id);
            if ($saleCheque !== null) {
                $saleCheque->is_inactive = ActiveRecord::INACTIVE;
                $saleCheque->update(array('is_inactive'));

                foreach ($saleCheque->saleChequeDetails as $saleChequeDetail) {
                    $saleChequeDetail->is_inactive = ActiveRecord::INACTIVE;
                    $saleChequeDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAdmin() {
        $saleCheque = Search::bind(new SaleChequeHeader('search'), isset($_GET['SaleChequeHeader']) ? $_GET['SaleChequeHeader'] : array());

        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $saleCheque->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'saleChequeDetails:resetScope' => array(
                'with' => array('saleReceiptHeader:resetScope' => array(
                        'with' => 'customer:resetScope',
                    ),)
            )
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
            'saleCheque' => $saleCheque,
            'dataProvider' => $dataProvider,
            'customerId' => $customerId,
        ));
    }

    public function actionAjaxHtmlAmountData($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);

            $this->loadState($saleCheque);

            $amount = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleCheque->header, 'amount')));

            echo CJSON::encode(array(
                'amount' => $amount,
            ));
        }
    }

    public function actionAjaxHtmlReceiptData($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);

            $this->loadState($saleCheque);

            $saleChequeReceipt = $saleCheque->header->saleReceiptHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'customer:resetScope',
                ),
                    ));

            $saleCheque->generateCodeNumber($saleChequeReceipt->branch_id, date('m'), date('y'));

            $object = array(
                'sale_receipt_header_codeNumber' => $saleChequeReceipt->getCodeNumber(SaleReceiptHeader::CN_CONSTANT),
                'sale_cheque_codeNumber' => $saleCheque->header->getCodeNumber(SaleCheque::CN_CONSTANT),
                'sale_receipt_header_date' => Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($saleChequeReceipt->date)),
                'sale_receipt_header_customer' => $saleChequeReceipt->customer->company,
                'branch' => $saleChequeReceipt->branch->name,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlShowSaleReceipt($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);

            $this->loadState($saleCheque);

            $saleReceipt = SaleReceiptHeader::model()->findByPk(isset($_POST['SaleCheque']['sale_receipt_header_id']) ? $_POST['SaleCheque']['sale_receipt_header_id'] : '');
            if ($saleReceipt === null)
                $saleReceipt = SaleReceiptHeader::model();

            $this->renderPartial('_detail', array(
                'saleCheque' => $saleCheque,
                'saleReceipt' => $saleReceipt,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);
            $this->loadState($saleCheque);

            $saleCheque->generateCodeNumber($saleCheque->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleCheque->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleCheque->header->date)));
            $codeNumber = CHtml::encode($saleCheque->header->getCodeNumber(SaleChequeHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function actionAjaxHtmlAddSaleReceipt($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);

            $this->loadState($saleCheque);

            if (isset($_POST['SaleReceiptHeaderId']))
                $saleCheque->addDetail($_POST['SaleReceiptHeaderId']);

            $this->renderPartial('_detail', array(
                'saleCheque' => $saleCheque,
            ));
        }
    }

    public function actionAjaxHtmlRemoveDetail($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);
            $this->loadState($saleCheque);

            $saleCheque->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'saleCheque' => $saleCheque,
            ));
        }
    }

    public function actionAjaxHtmlResetDetail($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);
            $this->loadState($saleCheque);

            if (isset($_POST['CustomerId']))
                $saleCheque->resetDetail($_POST['CustomerId']);

            $this->renderPartial('_detail', array(
                'saleCheque' => $saleCheque,
            ));
        }
    }

    public function actionAjaxJsonAmount($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);

            $this->loadState($saleCheque);

            $amount = Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleCheque, 'totalAmount'));

            echo CJSON::encode(array(
                'amount' => $amount,
            ));
        }
    }

    public function actionAjaxJsonCustomer($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleCheque = $this->instantiate($id);
            $this->loadState($saleCheque);

//			if (!isset($_POST['SaleChequeDetail']))
//				$saleCheque->details = array();

            $customerId = (isset($_POST['SaleChequeHeader']['customer_id'])) ? $_POST['SaleChequeHeader']['customer_id'] : '';

            $customer = Customer::model()->findByPk($customerId);

            $object = array(
                'customer_id' => $customer->company,
                'customer_name' => $customer->name,
                'customer_address' => $customer->address,
            );
            echo CJSON::encode($object);
        }
    }

}
