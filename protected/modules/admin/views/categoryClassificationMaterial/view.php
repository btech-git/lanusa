<?php
$this->breadcrumbs = array(
	'Category Classification Materials'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryClassificationMaterial', 'url'=>array('create')),
	array('label'=>'Update CategoryClassificationMaterial', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryClassificationMaterial', 'url'=>array('admin')),
);
?>

<h1>View CategoryClassificationMaterial #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Classification',
			'value'=>$model->categoryClassification->name,
		),
		array(
			'label'=>'Material',
			'value'=>$model->material->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
