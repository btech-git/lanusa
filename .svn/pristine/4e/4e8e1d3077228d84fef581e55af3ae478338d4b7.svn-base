<?php
$this->breadcrumbs = array(
	'Disc Materials'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create DiscMaterial', 'url'=>array('create')),
	array('label'=>'Update DiscMaterial', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage DiscMaterial', 'url'=>array('admin')),
);
?>

<h1>View DiscMaterial #<?php echo $model->id; ?></h1>

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
