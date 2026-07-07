<?php
$this->breadcrumbs = array(
	'Customers'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>Create Customer</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'accounts' => $accounts
)); ?>