<?php
$this->breadcrumbs = array(
	'Thicknesses'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Thickness', 'url'=>array('create')),
	array('label'=>'Update Thickness', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Thickness', 'url'=>array('admin')),
);
?>

<h1>View Thickness #<?php echo $model->id; ?></h1>

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
