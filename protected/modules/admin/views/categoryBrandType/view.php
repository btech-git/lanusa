<?php
$this->breadcrumbs = array(
	'Category Brand Types'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrandType', 'url'=>array('create')),
	array('label'=>'Update CategoryBrandType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandType', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrandType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Brand',
			'value'=>$model->categoryBrand->name,
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
