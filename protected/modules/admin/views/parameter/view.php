<?php
$this->breadcrumbs = array(
	'Parameters'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Parameter', 'url'=>array('create')),
	array('label'=>'Update Parameter', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Parameter', 'url'=>array('admin')),
);
?>

<h1>View Parameter #<?php echo $model->id; ?></h1>

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
