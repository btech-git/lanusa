<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Master', 'url'=>array('/site/page', 'view'=>'master'), 'visible'=>Yii::app()->user->checkAccess('master')),
				array('label'=>'User Account', 'url'=>array('/admin/admin/admin'), 'visible'=>Yii::app()->user->checkAccess('administrator')),
                array('label'=>'Transaksi', 'url'=>array('/site/page', 'view'=>'transaction'), 'visible'=>Yii::app()->user->checkAccess('purchaseCreate') 
					|| Yii::app()->user->checkAccess('receiveCreate') 
					|| Yii::app()->user->checkAccess('purchaseReturnCreate') 
					|| Yii::app()->user->checkAccess('stockAdjustmentCreate') 
					|| Yii::app()->user->checkAccess('stockTransferCreate') 
					|| Yii::app()->user->checkAccess('saleReturnCreate') 
					|| Yii::app()->user->checkAccess('deliveryCreate') 
					|| Yii::app()->user->checkAccess('saleCreate')
				),
				array('label'=>'Keuangan', 'url'=>array('/site/page', 'view'=>'accounting'), 'visible'=>Yii::app()->user->checkAccess('adjustmentJournalCreate') 
					|| Yii::app()->user->checkAccess('purchaseReceiptCreate') 
					|| Yii::app()->user->checkAccess('purchasePaymentCreate') 
					|| Yii::app()->user->checkAccess('saleInvoiceCreate') 
					|| Yii::app()->user->checkAccess('saleReceiptCreate') 
					|| Yii::app()->user->checkAccess('saleChequeCreate') 
					|| Yii::app()->user->checkAccess('salePaymentCreate') 
					|| Yii::app()->user->checkAccess('saleDownpaymentCreate') 
					|| Yii::app()->user->checkAccess('cashExpenseCreate') 
					|| Yii::app()->user->checkAccess('cashDepositCreate') 
				),
				array('label'=>'Laporan', 'url'=>array('/site/page', 'view'=>'report') , 'visible'=>Yii::app()->user->checkAccess('purchaseReport') 
					|| Yii::app()->user->checkAccess('saleReport')
					|| Yii::app()->user->checkAccess('saleReturnReport') 
					|| Yii::app()->user->checkAccess('deliveryReport') 
					|| Yii::app()->user->checkAccess('receiveReport') 
					|| Yii::app()->user->checkAccess('purchaseReturnReport') 
					|| Yii::app()->user->checkAccess('stockAdjustmentReport') 
					|| Yii::app()->user->checkAccess('stockReport') 
					|| Yii::app()->user->checkAccess('stockTransferReport') 
					|| Yii::app()->user->checkAccess('purchaseReceiptReport') 
					|| Yii::app()->user->checkAccess('purchasePaymentReport') 
					|| Yii::app()->user->checkAccess('saleDownpaymentReport') 
					|| Yii::app()->user->checkAccess('saleInvoiceReport') 
					|| Yii::app()->user->checkAccess('saleReceiptReport') 
					|| Yii::app()->user->checkAccess('saleChequeReport') 
					|| Yii::app()->user->checkAccess('salePaymentReport') 
					|| Yii::app()->user->checkAccess('cashDepositReport') 
					|| Yii::app()->user->checkAccess('cashExpenseReport') 
					|| Yii::app()->user->checkAccess('adjustmentJournalReport') 
					|| Yii::app()->user->checkAccess('allAccountingReport')
					|| Yii::app()->user->checkAccess('allFinanceReport')
					|| Yii::app()->user->checkAccess('receivableReport')
				),
				array('label'=>'Revisi', 'url'=>array('/site/page', 'view'=>'edit'), 'visible'=>Yii::app()->user->checkAccess('purchaseEdit') 
					|| Yii::app()->user->checkAccess('saleEdit') 
					|| Yii::app()->user->checkAccess('deliveryEdit') 
					|| Yii::app()->user->checkAccess('saleReturnEdit') 
					|| Yii::app()->user->checkAccess('receiveEdit') 
					|| Yii::app()->user->checkAccess('purchaseReturnEdit') 
					|| Yii::app()->user->checkAccess('saleInvoiceEdit')
					|| Yii::app()->user->checkAccess('cashExpenseEdit') 
					|| Yii::app()->user->checkAccess('cashDepositEdit')
					|| Yii::app()->user->checkAccess('salesDownpaymentEdit') 
					|| Yii::app()->user->checkAccess('saleReceiptEdit') 
					|| Yii::app()->user->checkAccess('saleChequeEdit') 
					|| Yii::app()->user->checkAccess('salePaymentEdit') 
					|| Yii::app()->user->checkAccess('purchaseReceiptEdit') 
					|| Yii::app()->user->checkAccess('purchasePaymentEdit') 
					|| Yii::app()->user->checkAccess('adjustmentJournalEdit') 
				),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('style'=>'float: right'))
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by PT. Logam Nusantara Perkasa.<br/>
		All Rights Reserved.<br/>
		Powered by <?php echo CHtml::link('BloomingTech', 'http://www.bloomingtech.com'); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>