<?php
$this->breadcrumbs = array(
	'Payment Types'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage PaymentType', 'url'=>array('admin')),
);
?>

<h1>Create PaymentType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>