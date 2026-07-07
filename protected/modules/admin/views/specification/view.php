<?php
$this->breadcrumbs = array(
	'Specifications'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Specification', 'url'=>array('create')),
	array('label'=>'Update Specification', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Specification', 'url'=>array('admin')),
);
?>

<h1>View Specification #<?php echo $model->id; ?></h1>

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
