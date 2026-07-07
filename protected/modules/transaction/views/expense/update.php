<?php
	$this->breadcrumbs = array(
		'Expense'=>array('admin'),
		'Create',
	);
?>

<h1><?php echo $expenseHeaderText; ?></h1>

<?php echo $this->renderPartial('_form', array( 
	'expense' => $expense, 
	'expenseAccountCategory' => $expenseAccountCategory, 
	'account' => $account,
	'accountDataProvider' => $accountDataProvider,
)); ?>