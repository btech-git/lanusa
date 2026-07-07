<?php

class FeeInvoiceController extends CrudController {

    public function filters() {
        return array(
//            'access',
        );
    }

    public function filterAccess($filterChain) {
//        if ($filterChain->action->id === 'create' || $filterChain->action->id === '/completion/customer' || $filterChain->action->id === 'ajaxHtmlAddProduct' || $filterChain->action->id === 'ajaxJsonDiscountTaxTotal' || $filterChain->action->id === 'ajaxJsonDownpaymentTaxTotal' || $filterChain->action->id === 'ajaxJsonGrandTotal' || $filterChain->action->id === 'ajaxHtmlRemoveProduct' || $filterChain->action->id === 'ajaxJsonTaxTotal' || $filterChain->action->id === 'ajaxJsonTotal' || $filterChain->action->id === 'ajaxHtmlUpdateAllProduct' || $filterChain->action->id === 'memo' || $filterChain->action->id === 'view') {
//            if (!(Yii::app()->user->checkAccess('saleCreate') || Yii::app()->user->checkAccess('saleEdit')))
//                $this->redirect(array('/site/login'));
//        }
//        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
//            if (!(Yii::app()->user->checkAccess('saleEdit')))
//                $this->redirect(array('/site/login'));
//        }
//        if ($filterChain->action->id === 'memoPicking' || $filterChain->action->id === 'adminWarehouse' || $filterChain->action->id === 'viewWarehouse') {
//            if (!(Yii::app()->user->checkAccess('pickingPrint')))
//                $this->redirect(array('/site/login'));
//        }
        $filterChain->run();
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new FeeInvoice;
        $model->date = date('Y-m-d');
        $model->admin_id = Yii::app()->user->id;

        $customer = Search::bind(new Customer('search'), isset($_GET['Customer']) ? $_GET['Customer'] : array());
        $dataProvider = $customer->search();

        if (isset($_POST['FeeInvoice']) && IdempotentManager::check()) {
            $model->attributes = $_POST['FeeInvoice'];
            $model->generateCodeNumber($model->branch_id, Yii::app()->dateFormatter->format('M', strtotime($model->date)), Yii::app()->dateFormatter->format('yy', strtotime($model->date)));
            
            if ($model->save() && IdempotentManager::build()->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'customer' => $customer,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        $customer = Search::bind(new Customer('search'), isset($_GET['Customer']) ? $_GET['Customer'] : array());
        $dataProvider = $customer->search();

        if (isset($_POST['FeeInvoice']) && IdempotentManager::check()) {
            $model->attributes = $_POST['FeeInvoice'];
            
            if ($model->save() && IdempotentManager::check()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'customer' => $customer,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionAdmin() {
        $model = Search::bind(new FeeInvoice('search'), isset($_GET['FeeInvoice']) ? $_GET['FeeInvoice'] : array());
        
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');

        $dataProvider = $model->search();
        $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);

        if (isset($_GET['FeeInvoice'])) {
            $model->attributes = $_GET['FeeInvoice'];
        }

        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionMemo($id) {
        $model = $this->loadModel($id);

        $this->memoToExcel($model);
    }

    protected function memoToExcel($model) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Invoice Lain2');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Invoice Lain2');

        $worksheet->getStyle('A2:F2')->getFont()->setBold(true);
        $worksheet->mergeCells('D2:G2');
        $worksheet->mergeCells('D3:G3');
        $worksheet->mergeCells('D4:G4');
        $worksheet->mergeCells('D5:G5');
        
        $worksheet->setCellValue('B2', 'Transaksi #');
        $worksheet->setCellValue("C2", ':');
        $worksheet->setCellValue("D2", $model->getCodeNumber(FeeInvoice::CN_CONSTANT));

        $worksheet->setCellValue("B3", 'Telah Terima dari');
        $worksheet->setCellValue("C3", ':');
        $worksheet->setCellValue("D3", $model->customer->company);

        $worksheet->setCellValue("B4", 'Uang sejumlah');
        $worksheet->setCellValue("C4", ':');
        $worksheet->setCellValue("D4", NumberWord::numberName(CHtml::value($model, 'fee_amount')));

        $worksheet->setCellValue("B5", 'Untuk pembayaran');
        $worksheet->setCellValue("C5", ':');
        $worksheet->setCellValue("D5", $model->note);

        $worksheet->setCellValue("F7", 'Jakarta,' . Yii::app()->dateFormatter->format("d MMMM yyyy", $model->date));

        $worksheet->setCellValue("B9", 'Rp');
        $worksheet->setCellValue("D9", $model->fee_amount);

        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="print_invoice_lain.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        Yii::app()->end();
    }

    public function actionAjaxJsonCustomer() {
        if (Yii::app()->request->isAjaxRequest) {
            $customerId = isset($_POST['FeeInvoice']['customer_id']) ? $_POST['FeeInvoice']['customer_id'] : '';
            $customer = Customer::model()->findByPk($customerId);
            
            $object = array(
                'customer_company' => $customer->company,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxJsonTaxAmount($id) {
        if (Yii::app()->request->isAjaxRequest) {
            if (empty($id)) {
                $model = new FeeInvoice();
            } else {
                $model = $this->loadModel($id);
            }
            
            if (isset($_POST['FeeInvoice'])) {
                $model->attributes = $_POST['FeeInvoice'];
            }
            
            $taxItemAmount = $model->getTaxItemAmount();
            $taxServiceAmount = $model->getTaxServiceValue();
            $taxItemFormatted = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $model->getTaxItemAmount()));
            $taxServiceFormatted = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $model->getTaxServiceValue()));

            echo CJSON::encode(array(
                'tax_item_amount' => $taxItemAmount,
                'tax_service_amount' => $taxServiceAmount,
                'tax_item_formatted' => $taxItemFormatted,
                'tax_service_formatted' => $taxServiceFormatted,
            ));
        }
    }

    public function loadModel($id) {
        $model = FeeInvoice::model()->findByPk($id);
        
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

}
