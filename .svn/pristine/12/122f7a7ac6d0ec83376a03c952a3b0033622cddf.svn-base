<?php

class CategoryClassificationMaterialController extends CrudController
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
		$model = new CategoryClassificationMaterial;

		if (isset($_POST['CategoryClassificationMaterial']))
		{
			$model->attributes = $_POST['CategoryClassificationMaterial'];
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

		if (isset($_POST['CategoryClassificationMaterial']))
		{
			$model->attributes = $_POST['CategoryClassificationMaterial'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	public function actionAdmin()
	{
		$model = new CategoryClassificationMaterial('search');
		$model->unsetAttributes();
		if (isset($_GET['CategoryClassificationMaterial']))
			$model->attributes = $_GET['CategoryClassificationMaterial'];
		
		$dataProvider = $model->search();
		$dataProvider->model->resetScope();

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function loadModel($id)
	{
		$model = CategoryClassificationMaterial::model()->resetScope()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}
