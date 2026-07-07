<?php
	if($expense->header->is_bank){
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

<h1><?php echo $expenseHeaderText; ?></h1>

<?php echo $this->renderPartial('_form', array( 
	'expense' => $expense, 
	'expenseHeaderText' => $expenseHeaderText, 
	'expenseAccountCategory' => $expenseAccountCategory,
	'account' => $account,
	'accountDataProvider' => $accountDataProvider,
)); ?>