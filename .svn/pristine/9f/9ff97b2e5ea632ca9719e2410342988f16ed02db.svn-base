<?php

class UnitController extends CrudController
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
		$model = new Unit;

		if (isset($_POST['Unit']))
		{
			$model->attributes = $_POST['Unit'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['Unit']))
		{
			$model->attributes = $_POST['Unit'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	public function actionAdmin()
	{
		$model = new Unit('search');
		$model->unsetAttributes();
		if (isset($_GET['Unit']))
			$model->attributes = $_GET['Unit'];
		
		$dataProvider = $model->search();
		$dataProvider->model->resetScope();

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function loadModel($id)
	{
		$model = Unit::model()->resetScope()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}
