<?php

class TransferController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create' || $filterChain->action->id === 'view' || $filterChain->action->id === '/completion/product' || $filterChain->action->id === 'ajaxHtmlAddProduct' || $filterChain->action->id === 'ajaxHtmlRemoveProduct' || $filterChain->action->id === 'ajaxHtmlUpdateAllProduct') {
            if (!(Yii::app()->user->checkAccess('stockTransferCreate')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $transfer = $this->instantiate(null);
        $transfer->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($transfer);
            $transfer->generateCodeNumber($transfer->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($transfer->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($transfer->header->date)));

            if ($transfer->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $transfer->header->id));
        }

        $this->render('create', array(
            'transfer' => $transfer,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $transfer = $this->instantiate($id);
        $transfer->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($transfer);
            if ($transfer->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $transfer->header->id));
        }

        $this->render('create', array(
            'transfer' => $transfer,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {
        $transfer = $this->loadModel($id);

        $warehouseIdFrom = $transfer->warehouseIdFrom(array('scopes' => 'resetScope'));
        $warehouseIdTo = $transfer->warehouseIdTo(array('scopes' => 'resetScope'));
        $branch = $transfer->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('transfer_header_id', $transfer->id);
        $detailsDataProvider = new CActiveDataProvider('TransferDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'transfer' => $transfer,
            'warehouseIdFrom' => $warehouseIdFrom,
            'warehouseIdTo' => $warehouseIdTo,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionAjaxHtmlAddProduct($nt, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $transfer = $this->instantiate($id);

            $this->loadState($transfer);

            if (isset($_POST['ProductId']))
                $transfer->addDetail($_POST['ProductId']);

            $this->renderPartial('_detail', array(
                'transfer' => $transfer,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($index, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $transfer = $this->instantiate($id);

            $this->loadState($transfer);

            $transfer->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'transfer' => $transfer,
            ));
        }
    }

    public function actionAjaxHtmlUpdateAllProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $transfer = $this->instantiate($id);

            $this->loadState($transfer);

            $this->renderPartial('_detail', array(
                'transfer' => $transfer,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $transfer = $this->instantiate($id);
            $this->loadState($transfer);

            $transfer->generateCodeNumber($transfer->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($transfer->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($transfer->header->date)));
            $codeNumber = CHtml::encode($transfer->header->getCodeNumber(TransferHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $transfer = new Transfer(new TransferHeader(), array());
        else {
            $transferHeader = $this->loadModel($id);
            $transfer = new Transfer($transferHeader, $transferHeader->transferDetails);
        }

        return $transfer;
    }

    public function loadModel($id) {
        $model = TransferHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($transfer) {
        if (isset($_POST['TransferHeader'])) {
            $transfer->header->attributes = $_POST['TransferHeader'];
        }
        if (isset($_POST['TransferDetail'])) {
            foreach ($_POST['TransferDetail'] as $item) {
                $detail = new TransferDetail();
                $detail->attributes = $item;
                $transfer->details[] = $detail;
            }
        } else
            $transfer->details = array();
    }

}
