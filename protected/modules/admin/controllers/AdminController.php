<?php

class AdminController extends CrudController
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
			|| $filterChain->action->id === 'admin')
		{
			if (!(Yii::app()->user->checkAccess('administrator')))
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
		$model = new Admin;
                $model->branch_id=1;

		if (isset($_POST['Admin']))
		{
			$model->attributes = $_POST['Admin'];
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
        $model->branch_id=1;


		if (isset($_POST['Admin']))
		{
			$model->attributes = $_POST['Admin'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	public function actionAdmin()
	{
		$model = new Admin('search');
        $model->branch_id=1;

		$model->unsetAttributes();
		if (isset($_GET['Admin']))
			$model->attributes = $_GET['Admin'];
		$dataProvider = $model->search();
		$dataProvider->model->resetScope();

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function loadModel($id)
	{
		$model = Admin::model()->resetScope()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}
