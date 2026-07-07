<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(array('login'));
        
		$id = (isset($_GET['PurchaseHeaderId'])) ? $_GET['PurchaseHeaderId'] : '';
		$start = (isset($_GET['Start'])) ? $_GET['Start'] : 1;
		$end = (isset($_GET['End'])) ? $_GET['End'] : 10;
		
		$number = '';
		$purchase = PurchaseHeader::model()->findByPk($id);
		if ($purchase !== null)
			$number = $purchase->number;
		
		$purchaseHeader = new PurchaseHeader('search');
		$purchaseHeader->unsetAttributes();  // clear any default values
		if (isset($_GET['PurchaseHeader']))
			$purchaseHeader->attributes = $_GET['PurchaseHeader'];
        
		$purchaseData = PurchaseHeader::makeChartData(15);
		
		$id = (isset($_GET['SaleHeaderId'])) ? $_GET['SaleHeaderId'] : '';
		$start = (isset($_GET['Start'])) ? $_GET['Start'] : 1;
		$end = (isset($_GET['End'])) ? $_GET['End'] : 10;
		
		$number = '';
		$order = SaleHeader::model()->findByPk($id);
		if ($order !== null)
			$number = $order->number;
		
		$orderHeader = new SaleHeader('search');
		$orderHeader->unsetAttributes();  // clear any default values
		if (isset($_GET['SaleHeader']))
			$orderHeader->attributes = $_GET['SaleHeader'];
		
		$orderData = SaleHeader::makeChartData(15);
			
		$this->render('index', array(
			'id' => $id,
			'number' => $number,
			'purchaseHeader' => $purchaseHeader,
			'orderHeader' => $orderHeader,
            
			'chartData' => json_encode($purchaseData),
			'chartAxisX' => json_encode(PurchaseHeader::makeChartAxisX($purchaseData['data'])),
			'chartAxisY' => json_encode(PurchaseHeader::makeChartAxisY($purchaseData['data'], 10)),
			
			'chartSaleData' => json_encode($orderData),
			'chartSaleAxisX' => json_encode(SaleHeader::makeChartAxisX($orderData['data'])),
			'chartSaleAxisY' => json_encode(SaleHeader::makeChartAxisY($orderData['data'], 10)),
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
