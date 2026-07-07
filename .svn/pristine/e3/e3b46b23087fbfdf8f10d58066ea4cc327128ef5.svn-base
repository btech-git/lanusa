<?php

class PurchaseReturnController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view' || $filterChain->action->id === 'create' || $filterChain->action->id === 'ajaxHtmlRemoveProduct' || $filterChain->action->id === 'ajaxHtmlReturnData' || $filterChain->action->id === 'ajaxHtmlAddReceive' || $filterChain->action->id === 'ajaxJsonTotal' || $filterChain->action->id === 'ajaxJsonGrandTotal' || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('purchaseReturnCreate') || Yii::app()->user->checkAccess('purchaseReturnEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('purchaseReturnEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $purchaseReturn = $this->instantiate(null);
        $purchaseReturn->header->admin_id = Yii::app()->user->id;

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $receiveHeader = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());
        $supplierCompany = isset($_GET['SupplierCompany']) ? $_GET['SupplierCompany'] : '';

        $dataProvider = $receiveHeader->searchByPurchaseReturn();
        $dataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => 'supplier:resetScope'
            ),
        );

        $dataProvider->criteria->addCondition("supplier.company LIKE :company");
        $dataProvider->criteria->params[':company'] = "%{$supplierCompany}%";

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseReturn);
            $purchaseReturn->header->branch_id = ($purchaseReturn->header->receiveHeader === null) ? '' : $purchaseReturn->header->receiveHeader->branch_id;
            $purchaseReturn->generateCodeNumber($purchaseReturn->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseReturn->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseReturn->header->date)));

            if ($purchaseReturn->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseReturnMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseReturn->header->id));
            }
        }

        $this->render('create', array(
            'purchaseReturn' => $purchaseReturn,
            'receiveHeader' => $receiveHeader,
//			'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'product' => $product,
            'supplierCompany' => $supplierCompany,
        ));
    }

    public function actionUpdate($id) {
        $purchaseReturn = $this->instantiate($id);
        $purchaseReturn->header->admin_id = Yii::app()->user->id;

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $receiveHeader = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());
        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $receiveHeader->normalizeCnMonthBy($cnMonth);

        $dataProvider = $receiveHeader->searchByPurchaseReturn();
        $dataProvider->criteria->with = array(
            'purchaseHeader:resetScope' => array(
                'with' => 'supplier:resetScope'
            ),
        );

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($purchaseReturn);

            if ($purchaseReturn->save(Yii::app()->db)) {
                Yii::app()->session['PurchaseReturnMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $purchaseReturn->header->id));
            }
        }

        $this->render('update', array(
            'purchaseReturn' => $purchaseReturn,
            'receiveHeader' => $receiveHeader,
            'cnMonth' => strtoupper($cnMonth),
            'dataProvider' => $dataProvider,
            'product' => $product,
        ));
    }

    public function actionView($id) {
        $purchaseReturn = $this->loadModel($id);

        $receiveHeader = $purchaseReturn->receiveHeader(array(
            'scopes' => 'resetScope',
            'with' => array(
                'purchaseHeader:resetScope' => array(
                    'with' => 'supplier:resetScope',
                ),
            ),
        ));
        $warehouse = $purchaseReturn->warehouse(array('scopes' => 'resetScope'));
        $branch = $purchaseReturn->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('purchase_return_header_id', $purchaseReturn->id);
        $detailsDataProvider = new CActiveDataProvider('PurchaseReturnDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'purchaseReturn' => $purchaseReturn,
            'receiveHeader' => $receiveHeader,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['PurchaseReturnMemoAllowed']) && Yii::app()->session['PurchaseReturnMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('PurchaseReturnMemoAllowed');

        $purchaseReturn = $this->loadModel($id);

        $receiveHeader = $purchaseReturn->receiveHeader(array(
            'scopes' => 'resetScope',
            'with' => array(
                'purchaseHeader:resetScope' => array(
                    'with' => 'supplier:resetScope',
                ),
            ),
        ));
        $warehouse = $purchaseReturn->warehouse(array('scopes' => 'resetScope'));
        $branch = $purchaseReturn->branch(array('scopes' => 'resetScope'));

        $purchaseReturnDetails = $purchaseReturn->purchaseReturnDetails(array(
            'with' => array(
                'product:resetScope' => array(
                    'with' => 'unit:resetScope',
                ),
            ),
        ));

        $this->render('memo', array(
            'purchaseReturn' => $purchaseReturn,
            'receiveHeader' => $receiveHeader,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'purchaseReturnDetails' => $purchaseReturnDetails,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $purchaseReturn = $this->loadModel($id);
            if ($purchaseReturn !== null) {
                $purchaseReturn->is_inactive = !$purchaseReturn->is_inactive;
                $purchaseReturn->update(array('is_inactive'));

                foreach ($purchaseReturn->purchaseReturnDetails as $purchaseReturnDetail) {
                    $purchaseReturnDetail->is_inactive = ActiveRecord::INACTIVE;
                    $purchaseReturnDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAdmin() {
        $purchaseReturn = Search::bind(new PurchaseReturnHeader('search'), isset($_GET['PurchaseReturnHeader']) ? $_GET['PurchaseReturnHeader'] : array());

        $supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';
        $warehouseId = (isset($_GET['WarehouseId'])) ? $_GET['WarehouseId'] : '';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $purchaseReturn->resetScope()->searchWithPaging();
        $dataProvider = $purchaseReturn->search();
        $dataProvider->criteria->with = array(
            'receiveHeader:resetScope' => array(
                'with' => array(
                    'purchaseHeader:resetScope' => array(
                        'with' => 'supplier:resetScope',
                    ),
                ),
            ),
            'warehouse:resetScope',
        );
        $dataProvider->criteria->compare('purchaseHeader.supplier_id', $supplierId);
        $dataProvider->criteria->compare('warehouse_id', $warehouseId);

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }

        $this->render('admin', array(
            'purchaseReturn' => $purchaseReturn,
            'dataProvider' => $dataProvider,
            'supplierId' => $supplierId,
            'warehouseId' => $warehouseId,
        ));
    }

    public function actionAjaxHtmlAddReceive($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);

            $this->loadState($purchaseReturn);

            if (isset($_POST['PurchaseReturnHeader']['receive_header_id']))
                $purchaseReturn->addReceive($_POST['PurchaseReturnHeader']['receive_header_id']);

            $this->renderPartial('_detail', array(
                'purchaseReturn' => $purchaseReturn,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($index, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);

            $this->loadState($purchaseReturn);

            $purchaseReturn->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'purchaseReturn' => $purchaseReturn,
            ));
        }
    }

    public function actionAjaxHtmlReturnData($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);

            $this->loadState($purchaseReturn);

            $returnReceive = $purchaseReturn->header->receiveHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'purchaseHeader:resetScope' => array(
                        'with' => 'supplier:resetScope',
                    ),
                ),
            ));

            $purchaseReturn->generateCodeNumber($returnReceive->branch_id, date('m'), date('y'));

            $object = array(
                'receive_code_number' => $returnReceive->getCodeNumber(ReceiveHeader::CN_CONSTANT),
                'purchase_return_codeNumber' => $purchaseReturn->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT),
                'supplier_company' => $returnReceive->purchaseHeader->supplier->company,
                'branch' => $returnReceive->branch->name,
            );
            echo CJSON::encode($object);
        }
    }

    public function actionAjaxJsonTotal($index, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);

            $this->loadState($purchaseReturn);

            if (isset($_POST['PurchaseReturnHeader']['receive_header_id'])) {
                $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->details[$index]->getTotal($_POST['PurchaseReturnHeader']['receive_header_id'])));
                $subTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getSubTotal($_POST['PurchaseReturnHeader']['receive_header_id'])));
                $tax = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getCalculatedTax($_POST['PurchaseReturnHeader']['receive_header_id'])));
                $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getGrandTotal($_POST['PurchaseReturnHeader']['receive_header_id'])));
            } else {
                $total = 0;
                $subTotal = 0;
                $tax = 0;
                $grandTotal = 0;
            }

            echo CJSON::encode(array(
                'total' => $total,
                'tax' => $tax,
                'subTotal' => $subTotal,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionAjaxJsonGrandTotal($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);

            $this->loadState($purchaseReturn);

            if (isset($_POST['PurchaseReturnHeader']['receive_header_id'])) {
                $tax = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getCalculatedTax($_POST['PurchaseReturnHeader']['receive_header_id'])));
                $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getGrandTotal($_POST['PurchaseReturnHeader']['receive_header_id'])));
            } else {
                $tax = 0;
                $grandTotal = 0;
            }

            echo CJSON::encode(array(
                'tax' => $tax,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $purchaseReturn = $this->instantiate($id);
            $this->loadState($purchaseReturn);

            $purchaseReturn->generateCodeNumber($purchaseReturn->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($purchaseReturn->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($purchaseReturn->header->date)));
            $codeNumber = CHtml::encode($purchaseReturn->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $purchaseReturn = new PurchaseReturn(new PurchaseReturnHeader(), array());
        else {
            $purchaseReturnHeader = $this->loadModel($id);
            $purchaseReturn = new PurchaseReturn($purchaseReturnHeader, $purchaseReturnHeader->purchaseReturnDetails);
        }

        return $purchaseReturn;
    }

    public function loadModel($id) {
        $model = PurchaseReturnHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState($purchaseReturn) {
        if (isset($_POST['PurchaseReturnHeader'])) {
            $purchaseReturn->header->attributes = $_POST['PurchaseReturnHeader'];
        }
        if (isset($_POST['PurchaseReturnDetail'])) {
            foreach ($_POST['PurchaseReturnDetail'] as $i => $item) {
                if (isset($purchaseReturn->details[$i]))
                    $purchaseReturn->details[$i]->attributes = $item;
                else {
                    $detail = new PurchaseReturnDetail();
                    $detail->attributes = $item;
                    $purchaseReturn->details[] = $detail;
                }
            }
            if (count($_POST['PurchaseReturnDetail']) < count($purchaseReturn->details))
                array_splice($purchaseReturn->details, $i + 1);
        } else
            $purchaseReturn->details = array();
    }

}
