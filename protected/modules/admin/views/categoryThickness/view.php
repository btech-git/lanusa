<?php
$this->breadcrumbs = array(
	'Category Thicknesses'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryThickness', 'url'=>array('create')),
	array('label'=>'Update CategoryThickness', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryThickness', 'url'=>array('admin')),
);
?>

<h1>View CategoryThickness #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
		),
		array(
			'label'=>'Thickness',
			'value'=>$model->thickness->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
