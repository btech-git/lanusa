<?php
$this->breadcrumbs = array(
	'Ranges'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Range', 'url'=>array('create')),
	array('label'=>'Update Range', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Range', 'url'=>array('admin')),
);
?>

<h1>View Range #<?php echo $model->id; ?></h1>

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
