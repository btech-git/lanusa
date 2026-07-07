<?php
$this->breadcrumbs = array(
	'Body Types'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create BodyType', 'url'=>array('create')),
	array('label'=>'Update BodyType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage BodyType', 'url'=>array('admin')),
);
?>

<h1>View BodyType #<?php echo $model->id; ?></h1>

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
