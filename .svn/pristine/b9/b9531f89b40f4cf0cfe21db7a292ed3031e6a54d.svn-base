<?php
$this->breadcrumbs = array(
	'Account Category Types'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create AccountCategoryType', 'url'=>array('create')),
	array('label'=>'Update AccountCategoryType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage AccountCategoryType', 'url'=>array('admin')),
);
?>

<h1>View AccountCategoryType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
