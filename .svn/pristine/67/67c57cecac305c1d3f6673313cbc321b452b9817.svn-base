<?php
$this->breadcrumbs = array(
	'Handlings'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Handling', 'url'=>array('create')),
	array('label'=>'Update Handling', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Handling', 'url'=>array('admin')),
);
?>

<h1>View Handling #<?php echo $model->id; ?></h1>

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
