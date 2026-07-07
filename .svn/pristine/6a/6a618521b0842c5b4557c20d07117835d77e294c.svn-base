<?php
$this->breadcrumbs = array(
	'Classifications'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Classification', 'url'=>array('create')),
	array('label'=>'Update Classification', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Classification', 'url'=>array('admin')),
);
?>

<h1>View Classification #<?php echo $model->id; ?></h1>

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
