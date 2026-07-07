<?php

class StartupCode extends CCodeModel
{
	public function requiredTemplates()
	{
		return array(
			'activerecord.php',
			'controller.php',
			'crudcontroller.php',
			'useridentity.php',
		);
	}

	public function prepare()
	{
		$this->files = array();
		$templatePath = $this->templatePath;
		
		$componentPath = Yii::getPathOfAlias('application.components');

		$this->files[] = new CCodeFile(
			$componentPath . '/ActiveRecord.php',
			$this->render($templatePath.'/activerecord.php')
		);
		$this->files[] = new CCodeFile(
			$componentPath . '/Controller.php',
			$this->render($templatePath.'/controller.php')
		);
		$this->files[] = new CCodeFile(
			$componentPath . '/CrudController.php',
			$this->render($templatePath.'/crudcontroller.php')
		);
		$this->files[] = new CCodeFile(
			$componentPath . '/UserIdentity.php',
			$this->render($templatePath.'/useridentity.php')
		);
	}
}