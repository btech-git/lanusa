<?php
$this->breadcrumbs = array(
	'Account Categories'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create AccountCategory', 'url'=>array('create')),
	array('label'=>'Update AccountCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage AccountCategory', 'url'=>array('admin')),
);
?>

<h1>View AccountCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
			'label'=>'Account Category Type',
			'value'=>$model->accountCategoryType->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
