<?php
$this->breadcrumbs = array(
	'Category Materials'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryMaterial', 'url'=>array('create')),
	array('label'=>'Update CategoryMaterial', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterial', 'url'=>array('admin')),
);
?>

<h1>View CategoryMaterial #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
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
