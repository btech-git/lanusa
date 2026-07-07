<?php
$this->breadcrumbs = array(
	'Connections'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Connection', 'url'=>array('create')),
	array('label'=>'Update Connection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Connection', 'url'=>array('admin')),
);
?>

<h1>View Connection #<?php echo $model->id; ?></h1>

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
