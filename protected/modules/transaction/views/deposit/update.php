<?php
	$this->breadcrumbs = array(
		'Deposit'=>array('admin'),
		'Create',
	);
?>

<h1><?php echo $depositHeaderText; ?></h1>

<?php echo $this->renderPartial('_form', array( 
	'deposit' => $deposit, 
	'depositHeaderText' => $depositHeaderText, 
	'depositAccountCategory' => $depositAccountCategory, 
	'account' => $account,
	'accountDataProvider' => $accountDataProvider,
)); ?>
