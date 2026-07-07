<?php

class IndentController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create' || $filterChain->action->id === '/completion/customer' || $filterChain->action->id === 'ajaxHtmlAddProduct' || $filterChain->action->id === 'ajaxHtmlRemoveProduct' || $filterChain->action->id === 'ajaxJsonTotal' || $filterChain->action->id === 'view') {
            if (!(Yii::app()->user->checkAccess('ntDeliveryCreate') || Yii::app()->user->checkAccess('ntDeliveryEdit') || Yii::app()->user->checkAccess('tDeliveryCreate') || Yii::app()->user->checkAccess('tDeliveryEdit') || Yii::app()->user->checkAccess('tsDeliveryCreate') || Yii::app()->user->checkAccess('tsDeliveryEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin') {
            if (!(Yii::app()->user->checkAccess('ntDeliveryEdit') || Yii::app()->user->checkAccess('tDeliveryEdit') || Yii::app()->user->checkAccess('tsDeliveryEdit')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $indent = $this->instantiate(null);

        $indent->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($indent);
            $indent->generateCodeNumber($indent->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($indent->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($indent->header->date)));

            if ($indent->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $indent->header->id));
        }

        $this->render('create', array(
            'indent' => $indent,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $indent = $this->instantiate($id);
        $indent->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($indent);
            if ($indent->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $indent->header->id));
        }

        $this->render('update', array(
            'indent' => $indent,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {
        $indent = $this->loadModel($id);

        $customer = $indent->customer(array('scopes' => 'resetScope'));
        $branch = $indent->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('indent_header_id', $indent->id);
        $detailsDataProvider = new CActiveDataProvider('IndentDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'indent' => $indent,
            'customer' => $customer,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionAdmin() {
        $indent = Search::bind(new IndentHeader('search'), isset($_GET['IndentHeader']) ? $_GET['IndentHeader'] : array());

        $dataProvider = $indent->search();
        $dataProvider->criteria->with = array(
            'customer:resetScope',
        );

        $this->render('admin', array(
            'indent' => $indent,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $indentHeader = $this->loadModel($id);
            if ($indentHeader !== null) {
                $indentHeader->is_inactive = ActiveRecord::INACTIVE;
                $indentHeader->update(array('is_inactive'));
            }
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxHtmlAddProduct($id, $nt) {
        if (Yii::app()->request->isAjaxRequest) {
            $indent = $this->instantiate($id);

            $this->loadState($indent);

            if (isset($_POST['ProductId']))
                $indent->addDetail($_POST['ProductId']);

            $this->renderPartial('_detail', array(
                'indent' => $indent,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $indent = $this->instantiate($id);

            $this->loadState($indent);

            $indent->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'indent' => $indent,
            ));
        }
    }

    public function actionAjaxJsonTotal($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $indent = $this->instantiate($id);

            $this->loadState($indent);

            $unitPrice = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($indent->details[$index], 'unit_price')));
            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($indent->details[$index], 'total')));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $indent->grandTotal));

            echo CJSON::encode(array(
                'unitPrice' => $unitPrice,
                'total' => $total,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionCustomerCompletion() {
        echo CJSON::encode(Completion::customer($_GET['term']));
    }

    protected function reportGrandTotal($dataProvider) {
        $grandTotal = 0;

        foreach ($dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $indent = $this->instantiate($id);
            $this->loadState($indent);

            $indent->generateCodeNumber($indent->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($indent->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($indent->header->date)));
            $codeNumber = CHtml::encode($indent->header->getCodeNumber(IndentHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $indent = new Indent(new IndentHeader(), array());
        else {
            $indentHeader = $this->loadModel($id);
            $indent = new Indent($indentHeader, $indentHeader->indentDetails);
        }

        return $indent;
    }

    public function loadModel($id) {
        $model = IndentHeader::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    protected function loadState($indent) {
        if (isset($_POST['IndentHeader'])) {
            $indent->header->attributes = $_POST['IndentHeader'];
        }
        if (isset($_POST['IndentDetail'])) {
            foreach ($_POST['IndentDetail'] as $i => $item) {
                if (isset($indent->details[$i]))
                    $indent->details[$i]->attributes = $item;
                else {
                    $detail = new IndentDetail();
                    $detail->attributes = $item;
                    $indent->details[] = $detail;
                }
            }
            if (count($_POST['IndentDetail']) < count($indent->details))
                array_splice($indent->details, $i + 1);
        } else
            $indent->details = array();
    }

}
