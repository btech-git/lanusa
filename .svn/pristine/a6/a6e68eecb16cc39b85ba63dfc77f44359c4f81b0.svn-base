<?php
$this->breadcrumbs = array(
	'Category Types'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryType', 'url'=>array('create')),
	array('label'=>'Update CategoryType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryType', 'url'=>array('admin')),
);
?>

<h1>View CategoryType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
		),
		array(
			'label'=>'Type',
			'value'=>$model->type->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
