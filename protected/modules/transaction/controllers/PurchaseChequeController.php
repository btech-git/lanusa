<?php

class PurchaseChequeController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view' || $filterChain->action->id === 'create' || $filterChain->action->id === 'ajaxHtmlAddInvoice' || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('purchaseChequeCreate') || Yii::app()->user->checkAccess('purchaseChequeEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'admin') {
            if (!(Yii::app()->user->checkAccess('purchaseChequeEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $purchaseCheque = $this->instantiate(null);
        $purchaseCheque->header->admin_id = Yii::app()->user->id;

        $purchaseReceiptHeader = Search::bind(new PurchaseReceiptHeader('search'), isset($_GET['PurchaseReceiptHeader']) ? $_GET['PurchaseReceiptHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseReceiptHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $purchaseReceiptHeader->searchByPurchaseCheque();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        $error = false;

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseCheque);
            $purchaseCheque->header->branch_id = ($purchaseCheque->header->purchaseReceiptHeader === null) ? '' : $purchaseCheque->header->purchaseReceiptHeader->branch_id;
            $purchaseCheque->generateCodeNumber($purchaseCheque->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseCheque->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseCheque->header->date)));

            if ($purchaseCheque->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseChequeMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseCheque->header->id));
            } else
                $error = true;
        }

        $this->render('create', array(
            'purchaseCheque' => $purchaseCheque,
            'purchaseReceiptHeader' => $purchaseReceiptHeader,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'error' => $error,
        ));
    }

    public function actionUpdate($id) {
        $purchaseCheque = $this->instantiate($id);
        $purchaseCheque->header->admin_id = Yii::app()->user->id;

        $purchaseReceiptHeader = Search::bind(new PurchaseReceiptHeader('search'), isset($_GET['PurchaseReceiptHeader']) ? $_GET['PurchaseReceiptHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseReceiptHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $purchaseReceiptHeader->searchByPurchaseCheque();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        $error = false;

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseCheque);
//            $purchaseCheque->generateCodeNumber($purchaseCheque->header->branch_id, date('m'), date('y'));

            if ($purchaseCheque->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseChequeMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseCheque->header->id));
            } else
                $error = true;
        }

        $this->render('update', array(
            'purchaseCheque' => $purchaseCheque,
            'purchaseReceiptHeader' => $purchaseReceiptHeader,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'error' => $error,
        ));
    }

    public function actionAjaxJsonAmount($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseCheque = $this->instantiate($id);

            $this->loadState($purchaseCheque);

            $amount = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchaseCheque->header, 'amount')));

            echo CJSON::encode(array(
                'amount' => $amount,
            ));
        }
    }

    public function actionAjaxJsonPurchaseReceipt($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseCheque = $this->instantiate($id);

            $this->loadState($purchaseCheque);

            $purchaseChequeReceipt = $purchaseCheque->header->purchaseReceiptHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'supplier:resetScope',
                ),
            ));

            $purchaseCheque->generateCodeNumber($purchaseChequeReceipt->branch_id, date('m'), date('y'));

            $object = array(
                'purchase_receipt_header_codeNumber' => $purchaseChequeReceipt->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT),
                'purchase_cheque_codeNumber' => $purchaseCheque->header->getCodeNumber(PurchaseCheque::CN_CONSTANT),
                'purchase_receipt_header_date' => Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($purchaseChequeReceipt->date)),
                'purchase_receipt_header_supplier' => $purchaseChequeReceipt->supplier->company,
                'branch' => $purchaseChequeReceipt->branch->name,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlShowPurchaseReceipt($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseCheque = $this->instantiate($id);

            $this->loadState($purchaseCheque);

            $purchaseReceipt = PurchaseReceiptHeader::model()->findByPk(isset($_POST['PurchaseCheque']['purchase_receipt_header_id']) ? $_POST['PurchaseCheque']['purchase_receipt_header_id'] : '');
            if ($purchaseReceipt === null)
                $purchaseReceipt = PurchaseReceiptHeader::model();

            $this->renderPartial('_detail', array(
                'purchaseCheque' => $purchaseCheque,
                'purchaseReceipt' => $purchaseReceipt,
            ));
        }
    }

    public function actionView($id) {
        $purchaseCheque = $this->loadModel($id);

        $purchaseReceiptHeader = $purchaseCheque->purchaseReceiptHeader(array(
            'scopes' => 'resetScope',
            'with' => 'supplier:resetScope',
        ));
        $branch = $purchaseCheque->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('purchase_cheque_id', $purchaseCheque->id);

        $this->render('view', array(
            'purchaseCheque' => $purchaseCheque,
            'purchaseReceiptHeader' => $purchaseReceiptHeader,
            'branch' => $branch,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['PurchaseChequeMemoAllowed']) && Yii::app()->session['PurchaseChequeMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('PurchaseChequeMemoAllowed');

        $purchaseCheque = $this->loadModel($id);

        $branch = $purchaseCheque->branch(array('scopes' => 'resetScope'));
//		$bank = $purchaseCheque->bank(array('scopes' => 'resetScope'));
        $purchaseReceiptHeader = $purchaseCheque->purchaseReceiptHeader(array(
            'scopes' => 'resetScope',
            'with' => 'supplier:resetScope',
        ));

//        $purchaseChequeSupplier = ($purchaseCheque->is_non_tax) ? $purchaseCheque->purchaseReceiptHeader->supplier->name : $purchaseCheque->purchaseReceiptHeader->supplier->company;
//        $purchaseChequeHeaderText = ($purchaseCheque->is_non_tax) ? '' : 'PT. Lanusa';

        $this->render('memo', array(
            'purchaseCheque' => $purchaseCheque,
            'purchaseReceiptHeader' => $purchaseReceiptHeader,
            'branch' => $branch,
//			'bank' => $bank,
//            'purchaseChequeSupplier' => $purchaseChequeSupplier,
//            'purchaseChequeHeaderText' => $purchaseChequeHeaderText,
        ));
    }

    public function actionAdmin() {
        $purchaseCheque = Search::bind(new PurchaseCheque('search'), isset($_GET['PurchaseCheque']) ? $_GET['PurchaseCheque'] : array());

        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';

        $dataProvider = $purchaseCheque->search();
        $dataProvider->criteria->with = array(
            'purchaseReceiptHeader:resetScope' => array(
                'with' => 'supplier:resetScope',
            ),
        );
        $dataProvider->criteria->compare('purchaseReceiptHeader.supplier_id', $supplierId);

        $this->render('admin', array(
            'purchaseCheque' => $purchaseCheque,
            'dataProvider' => $dataProvider,
            'supplierId' => $supplierId,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $purchaseCheque = $this->loadModel($id);
            if ($purchaseCheque !== null) {
                $purchaseCheque->is_inactive = ActiveRecord::INACTIVE;
                $purchaseCheque->update(array('is_inactive'));
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseCheque = $this->instantiate($id);
            $this->loadState($purchaseCheque);

            $purchaseCheque->generateCodeNumber($purchaseCheque->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseCheque->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseCheque->header->date)));
            $codeNumber = CHtml::encode($purchaseCheque->header->getCodeNumber(PurchaseCheque::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $purchaseCheque = new PurchaseChequeTransaction(new PurchaseCheque(), array());
        else {
            $purchaseCheque = $this->loadModel($id);
            $purchaseCheque = new PurchaseChequeTransaction($purchaseCheque);
        }

        return $purchaseCheque;
    }

    public function loadModel($id) {
        $model = PurchaseCheque::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($purchaseCheque) {
        if (isset($_POST['PurchaseCheque'])) {
            $purchaseCheque->header->attributes = $_POST['PurchaseCheque'];
        }
    }

}
