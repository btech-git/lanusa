<?php

class SaleReturnController extends SelectionController {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'view'
                || $filterChain->action->id === 'create'
                || $filterChain->action->id === 'ajaxJsonReturn'
                || $filterChain->action->id === 'ajaxHtmlRemoveProduct'
                || $filterChain->action->id === 'ajaxJsonTotal'
                || $filterChain->action->id === 'ajaxJsonTaxTotal'
                || $filterChain->action->id === 'ajaxJsonGrandTotal'
                || $filterChain->action->id === 'memo') {
            if (!(Yii::app()->user->checkAccess('saleReturnCreate') || Yii::app()->user->checkAccess('saleReturnEdit')))
                $this->redirect(array('/site/login'));
        }
        if ($filterChain->action->id === 'delete' || $filterChain->action->id === 'admin' || $filterChain->action->id === 'update') {
            if (!(Yii::app()->user->checkAccess('saleReturnEdit')))
                $this->redirect(array('/site/login'));
        }
        $filterChain->run();
    }

    public function actionCreate() {
        $saleReturn = $this->instantiate(null);
        $saleReturn->header->admin_id = Yii::app()->user->id;

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $productDataProvider = $product->search();
        $productDataProvider->criteria->with = array(
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

        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $saleInvoice->normalizeCnMonthBy($cnMonth);

        $cnOrdinalSale = isset($_GET['CnOrdinalSale']) ? $_GET['CnOrdinalSale'] : '';
        $cnMonthSale = isset($_GET['CnMonthSale']) ? $_GET['CnMonthSale'] : '';
        $cnYearSale = isset($_GET['CnYearSale']) ? $_GET['CnYearSale'] : '';

        $cnMonthSaleNormalized = MonthlyTransactionActiveRecord::normalizeMonthBy($cnMonthSale);

        $customerName = isset($_GET['CustomerName']) ? $_GET['CustomerName'] : '';
        $saleInvoiceDataProvider = $saleInvoice->searchBySaleReturn();
        $saleInvoiceDataProvider->criteria->with = array(
            'deliveryHeader:resetScope' => array(
                'with' => array('saleHeader:resetScope' => array(
                        'with' => array('customer:resetScope')
                )),
            ),
                //		'board:resetScope',
        );

        $saleInvoiceDataProvider->criteria->compare('saleHeader.cn_year', $cnYearSale);
        $saleInvoiceDataProvider->criteria->compare('saleHeader.cn_month', $cnMonthSaleNormalized);
        $saleInvoiceDataProvider->criteria->compare('saleHeader.cn_ordinal', $cnOrdinalSale);
        $saleInvoiceDataProvider->criteria->compare('customer.name', $customerName, TRUE);

        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleReturn);
            $saleReturn->header->branch_id = ($saleReturn->header->saleInvoice === null) ? '' : $saleReturn->header->saleInvoice->branch_id;
            $saleReturn->generateCodeNumber($saleReturn->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleReturn->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleReturn->header->date)));

            if ($saleReturn->save(Yii::app()->db)) {
                Yii::app()->session['saleReturnMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleReturn->header->id));
            }
        }

        $this->render('create', array(
            'saleReturn' => $saleReturn,
            'saleInvoice' => $saleInvoice,
            'product' => $product,
            'cnMonth' => strtoupper($cnMonth),
            'saleInvoiceDataProvider' => $saleInvoiceDataProvider,
            'productDataProvider' => $productDataProvider,
            'cnOrdinalSale' => $cnOrdinalSale,
            'cnMonthSale' => strtoupper($cnMonthSale),
            'cnYearSale' => $cnYearSale,
            'customerName' => $customerName
        ));
    }

    public function actionUpdate($id) {
        $saleReturn = $this->instantiate($id);
        $saleReturn->header->admin_id = Yii::app()->user->id;

        $product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
        $productDataProvider = $product->search();
        $productDataProvider->criteria->with = array(
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

        $customerName = isset($_GET['CustomerName']) ? $_GET['CustomerName'] : '';

        $saleInvoice = Search::bind(new SaleInvoice('search'), isset($_GET['SaleInvoice']) ? $_GET['SaleInvoice'] : array());

        $cnMonth = isset($_GET['CnMonth']) ? $_GET['CnMonth'] : '';
        $saleInvoice->normalizeCnMonthBy($cnMonth);

        $saleInvoiceDataProvider = $saleInvoice->searchBysaleReturn();
        $saleInvoiceDataProvider->criteria->with = array(
            'deliveryHeader:resetScope',
//			'board:resetScope',
        );
        $saleInvoiceDataProvider->criteria->compare('customer.name', $customerName, TRUE);


        if (isset($_POST['Submit']) && IdempotentManager::check()) {
            $this->loadState($saleReturn);

            if ($saleReturn->save(Yii::app()->db)) {
                Yii::app()->session['saleReturnMemoAllowed'] = true;
                $this->redirect(array('view', 'id' => $saleReturn->header->id));
            }
        }

        $this->render('update', array(
            'saleReturn' => $saleReturn,
            'saleInvoice' => $saleInvoice,
            'product' => $product,
            'cnMonth' => strtoupper($cnMonth),
            'saleInvoiceDataProvider' => $saleInvoiceDataProvider,
            'productDataProvider' => $productDataProvider,
            'customerName' => $customerName
        ));
    }

    public function actionView($id) {
        $saleReturn = $this->loadModel($id);

        $saleInvoice = $saleReturn->saleInvoice(array(
            'scopes' => 'resetScope',
            'with' => array(
                'deliveryHeader:resetScope' => array(
                    'with' => array(
                        'saleHeader' => array(
                            'with' => 'customer:resetScope',
                        ),
                    ),
                ),
            ),
                ));
        $warehouse = $saleReturn->warehouse(array('scopes' => 'resetScope'));
        $branch = $saleReturn->branch(array('scopes' => 'resetScope'));

        $criteria = new CDbCriteria;
        $criteria->compare('sale_return_header_id', $saleReturn->id);
        $detailsDataProvider = new CActiveDataProvider('SaleReturnDetail', array(
                    'criteria' => $criteria,
                ));

        $detailsDataProvider->criteria->with = array(
            'product:resetScope' => array(
                'with' => array('unit:resetScope'),
            ),
        );

        $this->render('view', array(
            'saleReturn' => $saleReturn,
            'saleInvoice' => $saleInvoice,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'detailsDataProvider' => $detailsDataProvider,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $saleReturnHeader = $this->loadModel($id);
            if ($saleReturnHeader !== null) {
                $saleReturnHeader->is_inactive = ActiveRecord::INACTIVE;
                $saleReturnHeader->update(array('is_inactive'));

                foreach ($saleReturnHeader->saleReturnDetails as $saleReturnDetail) {
                    $saleReturnDetail->is_inactive = ActiveRecord::INACTIVE;
                    $saleReturnDetail->update(array('is_inactive'));
                }
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionMemo($id) {
        if (!(Yii::app()->user->checkAccess('administrator'))) {
            if (!(isset(Yii::app()->session['saleReturnMemoAllowed']) && Yii::app()->session['saleReturnMemoAllowed'] === true))
                $this->redirect(array('admin'));
        }

        Yii::app()->session->remove('saleReturnMemoAllowed');

        $saleReturn = $this->loadModel($id);

        $saleInvoice = $saleReturn->saleInvoice(array(
            'scopes' => 'resetScope',
            'with' => array(
                'deliveryHeader:resetScope' => array(
                    'with' => array(
                        'saleHeader:resetScope' => array(
                            'with' => 'customer:resetScope',
                        ),
                    ),
                ),
            ),
                ));
        $warehouse = $saleReturn->warehouse(array('scopes' => 'resetScope'));
        $branch = $saleReturn->branch(array('scopes' => 'resetScope'));

        $saleReturnDetails = $saleReturn->saleReturnDetails(array(
            'with' => array(
                'product:resetScope' => array(
                    'with' => 'unit:resetScope',
                ),
            ),
                ));

        $this->render('memo', array(
            'saleReturn' => $saleReturn,
            'saleInvoice' => $saleInvoice,
            'warehouse' => $warehouse,
            'branch' => $branch,
            'saleReturnDetails' => $saleReturnDetails,
        ));
    }

    public function actionAdmin() {
        $saleReturn = Search::bind(new SaleReturnHeader('search'), isset($_GET['SaleReturnHeader']) ? $_GET['SaleReturnHeader'] : array());

        $customerId = (isset($_GET['CustomerId'])) ? $_GET['CustomerId'] : '';

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $dataProvider = $saleReturn->resetScope()->searchWithPaging();
        
        $dataProvider->criteria->compare('customer_id', $customerId);
        $dataProvider->criteria->compare('t.branch_id', $saleReturn->branch_id);
        
        $dataProvider->criteria->with = array(
            'saleInvoice:resetScope' => array(
                'with' => array('deliveryHeader:resetScope' => array(
                        'with' => array('saleHeader:resetScope' => array(
                                'with' => array('customer:resetScope')
                        )),
                    ),),
            ),
            'warehouse:resetScope',
        );

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';

        if ($startDate != '' || $endDate != '') {
            $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
            $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

            $dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        }
        $this->render('admin', array(
            'saleReturn' => $saleReturn,
            'dataProvider' => $dataProvider,
            'customerId' => $customerId,
        ));
    }

    public function actionAjaxHtmlAddProduct($id, $nt) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            if (isset($_POST['SaleReturnHeader']['sale_invoice_id']))
                $saleReturn->addDetail($_POST['SaleReturnHeader']['sale_invoice_id']);

            $this->renderPartial('_detail', array(
                'saleReturn' => $saleReturn,
            ));
        }
    }

    public function actionAjaxHtmlRemoveProduct($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            $saleReturn->removeDetailAt($index);

            $this->renderPartial('_detail', array(
                'saleReturn' => $saleReturn,
            ));
        }
    }

    public function actionAjaxJsonReturn($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            $returnInvoice = $saleReturn->header->saleInvoice(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'deliveryHeader:resetScope' => array(
                        'with' => array('saleHeader:resetScope' => array(
                                'with' => array('customer:resetScope')
                        ))
                    ),
                ),
                    ));

            $saleReturn->generateCodeNumber($returnInvoice->branch_id, date('m'), date('y'));

            $object = array(
                'sale_invoice_codeNumber' => $returnInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT),
                'sale_return_codeNumber' => $saleReturn->header->getCodeNumber(SaleReturnHeader::CN_CONSTANT),
                'customer_company' => $returnInvoice->deliveryHeader->saleHeader->customer->company,
                'branch' => $returnInvoice->branch->name,
            );

            echo CJSON::encode($object);
        }
    }

    public function actionAjaxJsonTotal($id, $index) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            $total = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->details[$index]->getTotal($_POST['SaleReturnHeader']['sale_invoice_id'])));
            $subTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getSubTotal($_POST['SaleReturnHeader']['sale_invoice_id'])));
            $tax = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getCalculatedTax($_POST['SaleReturnHeader']['sale_invoice_id'])));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getGrandTotal($_POST['SaleReturnHeader']['sale_invoice_id'])));

            echo CJSON::encode(array(
                'total' => $total,
                'tax' => $tax,
                'subTotal' => $subTotal,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionAjaxJsonTaxTotal($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            $tax = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getCalculatedTax($_POST['SaleReturnHeader']['sale_invoice_id'])));
            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getGrandTotal($_POST['SaleReturnHeader']['sale_invoice_id'])));

            echo CJSON::encode(array(
                'tax' => $tax,
                'grandTotal' => $grandTotal,
            ));
        }
    }

    public function actionAjaxJsonGrandTotal($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $saleReturn = $this->instantiate($id);

            $this->loadState($saleReturn);

            $grandTotal = CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleReturn->getGrandTotal($_POST['SaleReturnHeader']['sale_invoice_id'])));

            echo CJSON::encode(array(
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
            $saleReturn = $this->instantiate($id);
            $this->loadState($saleReturn);

            $saleReturn->generateCodeNumber($saleReturn->header->branch_id, Yii::app()->dateFormatter->format('M', strtotime($saleReturn->header->date)), Yii::app()->dateFormatter->format('yy', strtotime($saleReturn->header->date)));
            $codeNumber = CHtml::encode($saleReturn->header->getCodeNumber(SaleReturnHeader::CN_CONSTANT));

            echo CJSON::encode(array(
                'codeNumber' => $codeNumber,
            ));
        }
    }

    public function instantiate($id) {
        if (empty($id))
            $saleReturn = new SaleReturn(new SaleReturnHeader(), array());
        else {
            $saleReturnHeader = $this->loadModel($id);
            $saleReturn = new SaleReturn($saleReturnHeader, $saleReturnHeader->saleReturnDetails);
        }

        return $saleReturn;
    }

    public function loadModel($id) {
        $model = SaleReturnHeader::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function loadState($saleReturn) {
        if (isset($_POST['SaleReturnHeader']))
            $saleReturn->header->attributes = $_POST['SaleReturnHeader'];

        if (isset($_POST['SaleReturnDetail'])) {
            foreach ($_POST['SaleReturnDetail'] as $i => $item) {
                if (isset($saleReturn->details[$i]))
                    $saleReturn->details[$i]->attributes = $item;
                else {
                    $detail = new saleReturnDetail();
                    $detail->attributes = $item;
                    $saleReturn->details[] = $detail;
                }
            }
            if (count($_POST['SaleReturnDetail']) < count($saleReturn->details))
                array_splice($saleReturn->details, $i + 1);
        }
        else
            $saleReturn->details = array();
    }

}
