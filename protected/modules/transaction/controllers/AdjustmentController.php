<?php

class AdjustmentController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'create' || $filterChain->action->id === 'view' || $filterChain->action->id === 'ajaxHtmlAddProduct' || $filterChain->action->id === 'ajaxHtmlUpdateAllProduct' || $filterChain->action->id === 'ajaxHtmlRemoveProduct') {
            if (!(Yii::app()->user->checkAccess('stockAdjustmentCreate')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionCreate() {
        $adjustment = $this->instantiate(null);
        $adjustment->header->admin_id = Yii::app()->user->id;

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
            $this->loadState($adjustment);
            $adjustment->generateCodeNumber($adjustment->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($adjustment->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($adjustment->header->date)));

            if ($adjustment->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $adjustment->header->id));
        }

        $this->render('create', array(
            'adjustment' => $adjustment,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionUpdate($id) {
        $adjustment = $this->instantiate($id);

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
            $this->loadState($adjustment);

            if ($adjustment->save(Yii::app()->db))
                $this->redirect(array('view', 'id' => $adjustment->header->id));
        }

        $this->render('update', array(
            'adjustment' => $adjustment,
            'product' => $product,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {
        $adjustment = $this->loadModel($id);

        $warehouse = $adjustment->warehouse(array('scopes' => 'resetScope'));
        $branch = $adjustment->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('adjustment_header_id', $adjustment->id);
        $detailsDataProvider = new CActiveDataProvider('AdjustmentDetail', array(
            'criteria' => $criteria,
        ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'adjustment' => $adjustment,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionAdmin() {
        $adjustment = Search::bind(new AdjustmentHeader('search'), isset($_GET['AdjustmentHeader']) ? $_GET['AdjustmentHeader'] : array());
        $dataProvider = $adjustment->search();

        $this->render('admin', array(
            'adjustment' => $adjustment,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionAjaxHtmlAddProduct($nt, $id) {
        if (Yii::app()->request->isAjaxRequest) {
            $adjustment = $this->instantiate($id);

            $this->loadState($adjustment);

            if (isset($_POST['ProductId'])) {
                $adjustment->addDetail($_POST['ProductId']);
            }

            $this->renderPartial('_detail', array(
                'adjustment' => $adjustment,
            ));
        }
    }

    public function actionAjaxJsonDifference($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $adjustment = $this->instantiate($id);

            $this->loadState($adjustment);

            $quantityDifference = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $adjustment->details[$index]->getQuantityDifference($_POST['AdjustmentHeader']['warehouse_id'])));

            echo CJSON::encode(array(
                'quantityDifference' => $quantityDifference,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $adjustment = $this->instantiate($id);

            $this->loadState($adjustment);

            $adjustment->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'adjustment' => $adjustment,
            ));
        }
    }

    public function actionAjaxHtmlUpdateAllProduct($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $adjustment = $this->instantiate($id);

            $this->loadState($adjustment);

            $adjustment->updateProducts();

            $this->renderPartial('_detail', array(
                'adjustment' => $adjustment,
            ));
        }
    }

    public function actionAjaxJsonCodeNumber($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $adjustment = $this->instantiate($id);
            $this->loadState($adjustment);

            $adjustment->generateCodeNumber($adjustment->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($adjustment->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($adjustment->header->date)));
            $codeNumber = CHtml::encode($adjustment->header->getCodeNumber(AdjustmentHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $adjustment = new Adjustment(new AdjustmentHeader(), array());
        else {
            $adjustmentHeader = $this->loadModel($id);
            $adjustment = new Adjustment($adjustmentHeader, $adjustmentHeader->adjustmentDetails);
        }

        return $adjustment;
    }

    public function loadModel($id) {
        $model = AdjustmentHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($adjustment) {
        if (isset($_POST['AdjustmentHeader'])) {
            $adjustment->header->attributes = $_POST['AdjustmentHeader'];
        }
        if (isset($_POST['AdjustmentDetail'])) {
            foreach ($_POST['AdjustmentDetail'] as $i => $item) {
                if (isset($adjustment->details[$i]))
                    $adjustment->details[$i]->attributes = $item;
                else {
                    $detail = new AdjustmentDetail();
                    $detail->attributes = $item;
                    $adjustment->details[] = $detail;
                }
            }
            if (count($_POST['AdjustmentDetail']) < count($adjustment->details))
                array_splice($adjustment->details, $i + 1);
        } else
            $adjustment->details = array();
    }

}
