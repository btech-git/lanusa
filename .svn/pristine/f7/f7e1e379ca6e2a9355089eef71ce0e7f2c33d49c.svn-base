<?php

class SupplierController extends CrudController
{
	public $layout = '//layouts/column2';

	public function filters()
	{
		return array(
			'access',
		);
	}

	public function filterAccess($filterChain)
	{
		if ($filterChain->action->id === 'view' 
			|| $filterChain->action->id === 'create' 
			|| $filterChain->action->id === 'update' 
			|| $filterChain->action->id === 'admin' 
			|| $filterChain->action->id === 'delete')
		{
			if (!(Yii::app()->user->checkAccess('master')))
				$this->redirect(array('/site/login'));
		}

		$filterChain->run();
	}

	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate()
	{
		$model = new Supplier;
		
		$accounts = array();

		if (isset($_POST['Supplier']))
		{
			$model->attributes = $_POST['Supplier'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('create', array(
			'model'=>$model,
			'accounts' => $accounts
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		$criteria = new CDbCriteria();
		$criteria->compare('t.branch_id', $model->branch_id);
		$criteria->order = 't.code';
		$accounts = CHtml::listData(Account::model()->findAll($criteria), 'id', 'codeAndName');

		if (isset($_POST['Supplier']))
		{
			$model->attributes = $_POST['Supplier'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('update', array(
			'model'=>$model,
			'accounts' => $accounts
		));
	}

	public function actionAdmin()
	{
		$model = new Supplier('search');
		$model->unsetAttributes();
		if (isset($_GET['Supplier']))
			$model->attributes = $_GET['Supplier'];
		
		$dataProvider = $model->search();
		$dataProvider->model->resetScope();

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = $this->loadModel($id);
			$model->is_inactive = 1;
			$model->save();

			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id)
	{
		$model = Supplier::model()->resetScope()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	public function actionAjaxHtmlAccount($id) {
		if (Yii::app()->request->isAjaxRequest)
		{
			if (empty($id))
				$model = new Supplier;
			else
				$model = $this->loadModel($id);
			
			$branchId = isset($_POST['Supplier']['branch_id']) ? $_POST['Supplier']['branch_id'] : '';

			$criteria = new CDbCriteria();
			$criteria->compare('t.branch_id', $branchId);
			$criteria->order = 't.code';
			$accounts = CHtml::listData(Account::model()->findAll($criteria), 'id', 'codeAndName');

			$this->renderPartial('_accountDropDown', array(
				'model' => $model,
				'accounts' => $accounts
			));
		}
	}
}
