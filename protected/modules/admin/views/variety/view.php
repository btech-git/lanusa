<?php
$this->breadcrumbs = array(
	'Varieties'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Variety', 'url'=>array('create')),
	array('label'=>'Update Variety', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Variety', 'url'=>array('admin')),
);
?>

<h1>View Variety #<?php echo $model->id; ?></h1>

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
