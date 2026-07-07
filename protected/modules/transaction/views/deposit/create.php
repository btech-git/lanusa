<?php
	if($deposit->header->is_bank){
		$this->breadcrumbs = array(
			'Expense'=>array('admin','bank' => 1),
			'Create',
		);
	}else 
	{

		$this->breadcrumbs = array(
			'Expense'=>array('admin'),
			'Create',
		);
	}
?>

<h1><?php echo $depositHeaderText; ?></h1>

<?php echo $this->renderPartial('_form', array( 
	'deposit' => $deposit, 
	'depositHeaderText' => $depositHeaderText, 
	'depositAccountCategory' => $depositAccountCategory, 
	'account' => $account,
	'accountDataProvider' => $accountDataProvider,
)); ?>