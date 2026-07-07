<?php
$this->breadcrumbs = array(
	'Boards'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Board', 'url'=>array('create')),
	array('label'=>'Update Board', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Board', 'url'=>array('admin')),
);
?>

<h1>View Board #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'position',
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
