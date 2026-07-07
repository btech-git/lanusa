<?php
$this->breadcrumbs = array(
	'Accounts'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Account', 'url'=>array('create')),
	array('label'=>'Update Account', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Account', 'url'=>array('admin')),
);
?>

<h1>View Account #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		'name',
		'description',
		array(
			'label'=>'Account Category',
			'value'=>($model->accountCategory === null) ? '' : $model->accountCategory->name,
		),
		array(
			'label'=>'Branch',
			'value'=>($model->branch === null) ? '' : $model->branch->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
