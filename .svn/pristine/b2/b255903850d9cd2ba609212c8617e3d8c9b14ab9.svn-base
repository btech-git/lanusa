<?php
$this->breadcrumbs = array(
	'Payment Types'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create PaymentType', 'url'=>array('create')),
	array('label'=>'Update PaymentType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage PaymentType', 'url'=>array('admin')),
);
?>

<h1>View PaymentType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
