<?php

class ReceiveController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create'
                || $filterChain->action->id === 'ajaxJsonPurchase'
                || $filterChain->action->id === 'ajaxHtmlAddProduct'
                || $filterChain->action->id === 'ajaxHtmlRemoveProduct'
                || $filterChain->action->id === 'view'
                || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('receiveCreate') || Yii::app()->user->checkAccess('receiveEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!Yii::app()->user->checkAccess('receiveEdit'))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $receive = $this->instantiate(null);
        $receive->header->admin_id = Yii::app()->user->id;

        $purchaseHeader = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());

        $supplierCompany = isset($_GET['SupplierCompany']) ? $_GET['SupplierCompany'] : '';

        $dataProvider = $purchaseHeader->searchByReceive();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
            'branch:resetScope',
        );

        $dataProvider->criteria->addCondition("supplier.company LIKE :company");
        $dataProvider->criteria->params[':company'] = "%{$supplierCompany}%";

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($receive);
            $receive->header->branch_id = ($receive->header->purchaseHeader === null) ? '' : $receive->header->purchaseHeader->branch_id;
            $receive->generateCodeNumber($receive->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($receive->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($receive->header->date)));

            if ($receive->save(Yii::app()->db)) {
                Yii::app()->session['receiveMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $receive->header->id));
            }
        }

        $this->render('create', array(
            'receive' => $receive,
            'purchaseHeader' => $purchaseHeader,
//			'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'supplierCompany' => $supplierCompany,
        ));
    }

    public function actionUpdate($id) {
        $receive = $this->instantiate($id);
        $receive->header->admin_id = Yii::app()->user->id;

        $purchaseHeader = Search::bind(new PurchaseHeader('search'), isset($_GET['PurchaseHeader']) ? $_GET['PurchaseHeader'] : array());
        $supplierCompany = isset($_GET['SupplierCompany']) ? $_GET['SupplierCompany'] : '';
        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $purchaseHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $purchaseHeader->searchByReceive();
        $dataProvider->criteria->with = array(
            'supplier:resetScope',
        );

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($receive);

            if ($receive->save(Yii::app()->db)) {
                Yii::app()->session['receiveMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $receive->header->id));
            }
        }

        $this->render('update', array(
            'receive' => $receive,
            'purchaseHeader' => $purchaseHeader,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'supplierCompany' => $supplierCompany
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $receive = $this->loadModel($id);
            if ($receive !== null) {
                Inventory::model()->deleteAllByAttributes(array(
                    'transaction_ordinal' => $receive->cn_ordinal,
                    'transaction_month' => $receive->cn_month,
                    'transaction_year' => $receive->cn_year,
                    'branch_id' => $receive->branch_id,
                    'transaction_type' => 1,
                ));

                $receive->is_inactive = ActiveRecord::INACTIVE;
                $receive->update(array('is_inactive'));

                foreach ($receive->receiveDetails as $receiveDetail) {
                    $receiveDetail->is_inactive = ActiveRecord::INACTIVE;
                    $receiveDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionView($id) {
        $receive = $this->loadModel($id);

        $purchaseHeader = $receive->purchaseHeader(array(
            'scopes' => 'resetScope',
            'with' => 'supplier:resetScope',
                ));

        $branch = $receive->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('receive_header_id', $receive->id);
        $detailsDataProvider = new CActiveDataProvider('ReceiveDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'receive' => $receive,
            'purchaseHeader' => $purchaseHeader,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['receiveMemoAllowed']) && Yii::app()->session['receiveMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('receiveMemoAllowed');

        $receive = $this->loadModel($id);

        $warehouse = $receive->warehouse(array('scopes' => 'resetScope'));
        $branch = $receive->branch(array('scopes' => 'resetScope'));
        $purchaseHeader = $receive->purchaseHeader(array(
            'scopes' => 'resetScope',
            'with' => 'supplier:resetScope',
                ));

        $receiveDetails = $receive->receiveDetails(array(
            'with' => array(
                'product:resetScope' => array(
                    'with' => 'unit:resetScope',
                ),
            ),
                ));

//		$receiveHeaderText = ($receive->is_non_tax) ? '' : 'PT. Lanusa';
//		$receiveSupplier = ($receive->is_non_tax) ? $receive->purchaseHeader->supplier->name : $receive->purchaseHeader->supplier->company;

        $this->render('memo', array(
            'receive' => $receive,
            'purchaseHeader' => $purchaseHeader,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'receiveDetails' => $receiveDetails,
//			'receiveSupplier'=>$receiveSupplier,
//			'receiveHeaderText'=>$receiveHeaderText,
        ));
    }

    public function actionAdmin() {
        $receive = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());

        $supplierName = (isset($_GET['SupplierName'])) ? $_GET['SupplierName'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $receive->resetScope()->searchWithPaging();
        $dataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => 'supplier:resetScope',
            ),
        );

        $dataProvider->criteria->compare('supplier.name', $supplierName, TRUE);

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'receive' => $receive,
            'dataProvider' => $dataProvider,
            'supplierName' => $supplierName,
        ));
    }

    public function actionAjaxHtmlRemoveProduct($index, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $receive = $this->instantiate($id);

            $this->loadState($receive);

            $receive->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'receive' => $receive,
            ));
        }
    }

    public function actionAjaxJsonPurchase($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $receive = $this->instantiate($id);

            $this->loadState($receive);

            $purchase = $receive->header->purchaseHeader(array('scopes' => 'resetScope', 'with' => 'supplier:resetScope'));
            $supplier = $purchase->supplier(array('scopes' => 'resetScope'));

            $receive->generateCodeNumber($purchase->branch_id, Yii::app()->dateFormatter->format('M', strtotime($receive->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($receive->header->date)));

            $object = array(
                'purchase_header_codeNumber' => $purchase->getCodeNumber(PurchaseHeader::CN_CONSTANT),
                'receive_header_codeNumber' => $receive->header->getCodeNumber(ReceiveHeader::CN_CONSTANT),
                'supplier_company' => $supplier->company,
                'branch' => $purchase->branch->name,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxHtmlAddProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $receive = $this->instantiate($id);

            $this->loadState($receive);

            if (!isset($_POST['ReceiveDetail']))
                $receive->details = array();

            if (isset($_POST['ReceiveHeader']['purchase_header_id']))
                $receive->addDetail($_POST['ReceiveHeader']['purchase_header_id']);

            $this->renderPartial('_detail', array(
                'receive' => $receive,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $receive = $this->instantiate($id);
            $this->loadState($receive);

            $receive->generateCodeNumber($receive->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($receive->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($receive->header->date)));
            $codeNumber = CHtml::encode($receive->header->getCodeNumber(ReceiveHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $receive = new Receive(new ReceiveHeader(), array());
        else {
            $receiveHeader = $this->loadModel($id);
            $receive = new Receive($receiveHeader, $receiveHeader->receiveDetails);
        }

        return $receive;
    }

    public function loadModel($id) {
        $model = ReceiveHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState($receive) {
        if (isset($_POST['ReceiveHeader'])) {
            $receive->header->attributes = $_POST['ReceiveHeader'];
        }
        if (isset($_POST['ReceiveDetail'])) {
            foreach ($_POST['ReceiveDetail'] as $i => $item) {
                if (isset($receive->details[$i]))
                    $receive->details[$i]->attributes = $item;
                else {
                    $detail = new ReceiveDetail();
                    $detail->attributes = $item;
                    $receive->details[] = $detail;
                }
            }
            if (count($_POST['ReceiveDetail']) < count($receive->details))
                array_splice($receive->details, $i + 1);
        }
        else
            $receive->details = array();
    }

}
