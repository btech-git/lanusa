<?php
$this->breadcrumbs = array(
	'Category Varieties'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryVariety', 'url'=>array('create')),
	array('label'=>'Update CategoryVariety', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryVariety', 'url'=>array('admin')),
);
?>

<h1>View CategoryVariety #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
		),
		array(
			'label'=>'Variety',
			'value'=>$model->variety->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
