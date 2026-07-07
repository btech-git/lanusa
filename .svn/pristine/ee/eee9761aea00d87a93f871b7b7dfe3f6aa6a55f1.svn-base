<?php
$this->breadcrumbs = array(
	'Category Classification Connections'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryClassificationConnection', 'url'=>array('create')),
	array('label'=>'Update CategoryClassificationConnection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryClassificationConnection', 'url'=>array('admin')),
);
?>

<h1>View CategoryClassificationConnection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Classification',
			'value'=>$model->categoryClassification->name,
		),
		array(
			'label'=>'Connection',
			'value'=>$model->connection->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
