<?php
$this->breadcrumbs = array(
	'Category Connections'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryConnection', 'url'=>array('create')),
	array('label'=>'Update CategoryConnection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryConnection', 'url'=>array('admin')),
);
?>

<h1>View CategoryConnection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
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
