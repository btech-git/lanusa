<?php
$this->breadcrumbs = array(
	'Category Brands'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrand', 'url'=>array('create')),
	array('label'=>'Update CategoryBrand', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrand', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrand #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
		),
		array(
			'label'=>'Brand',
			'value'=>$model->brand->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
