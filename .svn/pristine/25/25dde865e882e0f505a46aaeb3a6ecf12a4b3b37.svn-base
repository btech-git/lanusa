<?php
$this->breadcrumbs = array(
	'Warehouses'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Warehouse', 'url'=>array('create')),
	array('label'=>'Update Warehouse', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Warehouse', 'url'=>array('admin')),
);
?>

<h1>View Warehouse #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'address',
		'phone',
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
