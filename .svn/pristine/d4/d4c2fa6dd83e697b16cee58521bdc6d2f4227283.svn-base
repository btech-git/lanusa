<?php

class PurchaseInvoiceController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view' || $filterChain->action->id === 'create' || $filterChain->action->id === 'ajaxHtmlAddInvoice' || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('purchaseInvoiceCreate') || Yii::app()->user->checkAccess('purchaseInvoiceEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('purchaseInvoiceEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $purchaseInvoice = $this->instantiate(null);

        $purchaseInvoice->header->admin_id = Yii::app()->user->id;

        $branchId = isset($_GET['PurchaseInvoiceHeader']['branch_id']) ? $_GET['PurchaseInvoiceHeader']['branch_id'] : '';

        $supplier = Search::bind(new Supplier('search'), isset($_GET['Supplier']) ? $_GET['Supplier'] : array());
        $supplierDataProvider = $supplier->search();
        $supplierDataProvider->criteria->with = array(
            'account:resetScope',
        );

        $purchaseHeader = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $purchaseHeader->searchByPurchaseInvoice();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        if (!empty($branchId)) {
            $supplierDataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $supplierDataProvider->criteria->params[':branch_id'] = $branchId;

            $dataProvider->criteria->addCondition("t.branch_id = :branch_id");
            $dataProvider->criteria->params[':branch_id'] = $branchId;
        }

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseInvoice);
            $purchaseInvoice->generateCodeNumber($purchaseInvoice->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseInvoice->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseInvoice->header->date)));

            if ($purchaseInvoice->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseInvoiceMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseInvoice->header->id));
            }
        }

        $this->render('create', array(
            'purchaseInvoice' => $purchaseInvoice,
            'purchaseHeader' => $purchaseHeader,
            'supplier' => $supplier,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'supplierDataProvider' => $supplierDataProvider,
        ));
    }

    public function actionUpdate($id) {
        $purchaseInvoice = $this->instantiate($id);

        $purchaseInvoice->header->admin_id = Yii::app()->user->id;

        $purchaseHeader = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $purchaseHeader->searchByPurchaseInvoice();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        $error = false;

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseInvoice);
//			$purchaseInvoice->generateCodeNumber($purchaseInvoice->header->branch_id, date('m'), date('y'));

            if ($purchaseInvoice->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseInvoiceMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseInvoice->header->id));
            } else
                $error = true;
        }

        $this->render('update', array(
            'purchaseInvoice' => $purchaseInvoice,
            'purchaseHeader' => $purchaseHeader,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'error' => $error,
        ));
    }

    public function actionView($id) {
        $purchaseInvoice = $this->loadModel($id);

        $supplier = $purchaseInvoice->supplier(array('scopes' => 'resetScope'));
        $branch = $purchaseInvoice->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('purchase_invoice_header_id', $purchaseInvoice->id);
        $detailsDataProvider = new CActiveDataProvider('PurchaseInvoiceDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => 'supplier:resetScope',
        ));

        $this->render('view', array(
            'purchaseInvoice' => $purchaseInvoice,
            'supplier' => $supplier,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['PurchaseInvoiceMemoAllowed']) && Yii::app()->session['PurchaseInvoiceMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('PurchaseInvoiceMemoAllowed');

        $purchaseInvoice = $this->loadModel($id);

        $supplier = $purchaseInvoice->supplier(array('scopes' => 'resetScope'));
        $branch = $purchaseInvoice->branch(array('scopes' => 'resetScope'));

        $purchaseInvoiceDetails = $purchaseInvoice->purchaseInvoiceDetails(array(
            'with' => array(
                'purchaseHeader:resetScope' => array(
                    'with' => 'supplier:resetScope',
                )),
        ));

        $this->render('memo', array(
            'purchaseInvoice' => $purchaseInvoice,
            'supplier' => $supplier,
            'branch' => $branch,
            'purchaseInvoiceDetails' => $purchaseInvoiceDetails,
        ));
    }

    public function actionAdmin() {
        $purchaseInvoice = Search::bind(new PurchaseInvoiceHeader('search'), isset($_GET['PurchaseInvoiceHeader']) ? $_GET['PurchaseInvoiceHeader'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseInvoice->normalizeCnMonthBy($cnMonth);

        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';

        $dataProvider = $purchaseInvoice->search();
        //$dataProvider->criteria->join = "INNER JOIN tblla_purchase_header purchaseHeader ON (t.purchase_header_id = purchaseHeader.id) AND (purchaseHeader.is_inactive = 0)";
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );
        $dataProvider->criteria->compare('supplier_id', $supplierId);

        $this->render('admin', array(
            'purchaseInvoice' => $purchaseInvoice,
            'dataProvider' => $dataProvider,
            'supplierId' => $supplierId,
            'cnMonth' => strtoupper($cnMonth),
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $purchaseInvoice = $this->loadModel($id);
            if ($purchaseInvoice !== null) {
                $purchaseInvoice->is_inactive = ActiveRecord::INACTIVE;
                $purchaseInvoice->update(array('is_inactive'));

                foreach ($purchaseInvoice->purchaseInvoiceDetails as $purchaseInvoiceDetail) {
                    $purchaseInvoiceDetail->is_inactive = ActiveRecord::INACTIVE;
                    $purchaseInvoiceDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseInvoice = $this->instantiate($id);
            $this->loadState($purchaseInvoice);

            $purchaseInvoice->generateCodeNumber($purchaseInvoice->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseInvoice->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseInvoice->header->date)));
            $codeNumber = CHtml::encode($purchaseInvoice->header->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function actionAjaxHtmlAddPurchase($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseInvoice = $this->instantiate($id);

            $this->loadState($purchaseInvoice);

            if (isset($_POST['PurchaseHeaderId']))
                $purchaseInvoice->addDetail($_POST['PurchaseHeaderId']);

            $this->renderPartial('_detail', array(
                'purchaseInvoice' => $purchaseInvoice,
            ));
        }
    }

    public function actionAjaxJsonSupplier($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseInvoice = $this->instantiate($id);

            $this->loadState($purchaseInvoice);

            $purchaseInvoiceSupplier = $purchaseInvoice->header->supplier(array('scopes' => 'resetScope'));

            $object = array(
                'supplier_id' => $purchaseInvoiceSupplier->company,
                'supplier_name' => $purchaseInvoiceSupplier->name,
                'supplier_address' => $purchaseInvoiceSupplier->address,
            );
            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlResetDetail($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseInvoice = $this->instantiate($id);

            $this->loadState($purchaseInvoice);

            if (isset($_POST['PurchaseInvoiceHeader']['supplier_id']))
                $purchaseInvoice->resetDetail($_POST['PurchaseInvoiceHeader']['supplier_id']);

            $this->renderPartial('_detail', array(
                'purchaseInvoice' => $purchaseInvoice,
            ));
        }
    }

    public function actionAjaxHtmlRemoveDetail($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseInvoice = $this->instantiate($id);

            $this->loadState($purchaseInvoice);

            $purchaseInvoice->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'purchaseInvoice' => $purchaseInvoice,
            ));
        }
    }

//    public function actionAjaxJsonPurchase($id)
//    {
//        if (Yii::app()->request->isAjaxRequest)
//        {
//            $purchaseInvoice = $this->instantiate($id);
//
//            $this->loadState($purchaseInvoice);
//
//			$purchaseData = $purchaseInvoice->header->purchaseHeader(array(
//				'with' => array(
//					'supplier:resetScope',
//				),
//			));
//			
//			$purchaseInvoice->generateCodeNumber($purchaseData->branch_id, date('m'), date('y'));
//
//            $object = array(
//                'purchase_header_codeNumber' => $purchaseData->getCodeNumber(PurchaseHeader::CN_CONSTANT),
//				'purchase_invoice_codeNumber' => $purchaseInvoice->header->getCodeNumber(PurchaseInvoice::CN_CONSTANT),
//                'supplier_company' => $purchaseData->supplier->company,
//				'branch' => $purchaseData->branch->name,
//            );
//
//            echo CJSON::encode($object);
//        }
//    }

    public function instantiate($id) {
        if (empty($id))
            $purchaseInvoice = new PurchaseInvoice(new PurchaseInvoiceHeader(), array());
        else {
            $purchaseInvoice = $this->loadModel($id);
            $purchaseInvoice = new PurchaseInvoice($purchaseInvoice, $purchaseInvoice->purchaseInvoiceDetails);
        }

        return $purchaseInvoice;
    }

    public function loadModel($id) {
        $model = PurchaseInvoiceHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState($purchaseInvoice) {
        if (isset($_POST['PurchaseInvoiceHeader'])) {
            $purchaseInvoice->header->attributes = $_POST['PurchaseInvoiceHeader'];
        }

        if (isset($_POST['PurchaseInvoiceDetail'])) {
            foreach ($_POST['PurchaseInvoiceDetail'] as $i => $item) {
                if (isset($purchaseInvoice->details[$i]))
                    $purchaseInvoice->details[$i]->attributes = $item;
                else {
                    $detail = new PurchaseInvoiceDetail();
                    $detail->attributes = $item;
                    $purchaseInvoice->details[] = $detail;
                }
            }
            if (count($_POST['PurchaseInvoiceDetail']) < count($purchaseInvoice->details))
                array_splice($purchaseInvoice->details, $i + 1);
        } else
            $purchaseInvoice->details = array();
    }

}
