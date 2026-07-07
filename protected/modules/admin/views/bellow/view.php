<?php
$this->breadcrumbs = array(
	'Bellows'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Bellow', 'url'=>array('create')),
	array('label'=>'Update Bellow', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Bellow', 'url'=>array('admin')),
);
?>

<h1>View Bellow #<?php echo $model->id; ?></h1>

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
