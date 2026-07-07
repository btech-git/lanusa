<?php
$this->breadcrumbs = array(
	'Connection Materials'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create ConnectionMaterial', 'url'=>array('create')),
	array('label'=>'Update ConnectionMaterial', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage ConnectionMaterial', 'url'=>array('admin')),
);
?>

<h1>View ConnectionMaterial #<?php echo $model->id; ?></h1>

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
