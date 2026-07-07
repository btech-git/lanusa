<?php
$this->breadcrumbs = array(
	'Category Classifications'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryClassification', 'url'=>array('create')),
	array('label'=>'Update CategoryClassification', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryClassification', 'url'=>array('admin')),
);
?>

<h1>View CategoryClassification #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
		),
		array(
			'label'=>'Classification',
			'value'=>$model->classification->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
