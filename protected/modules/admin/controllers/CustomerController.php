<?php

class CustomerController extends CrudController
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
		$model = new Customer;
		
		$accounts = array();

		if (isset($_POST['Customer']))
		{
			$model->attributes = $_POST['Customer'];
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

		if (isset($_POST['Customer']))
		{
			$model->attributes = $_POST['Customer'];
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
		$model = new Customer('search');
		$model->unsetAttributes();
		if (isset($_GET['Customer']))
			$model->attributes = $_GET['Customer'];
		
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
		$model = Customer::model()->resetScope()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
	
	
	public function actionAjaxHtmlAccount($id) {
		if (Yii::app()->request->isAjaxRequest)
		{
			if (empty($id))
				$model = new Customer;
			else
				$model = $this->loadModel($id);
			
			$branchId = isset($_POST['Customer']['branch_id']) ? $_POST['Customer']['branch_id'] : '';

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
