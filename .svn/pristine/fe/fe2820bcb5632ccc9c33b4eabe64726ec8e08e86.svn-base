<?php
$this->breadcrumbs = array(
	'Category Brand Bodys'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrandBody', 'url'=>array('create')),
	array('label'=>'Update CategoryBrandBody', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandBody', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrandBody #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Brand',
			'value'=>$model->categoryBrand->name,
		),
		array(
			'label'=>'Body Type',
			'value'=>$model->bodyType->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
