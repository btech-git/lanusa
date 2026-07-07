<?php
$this->breadcrumbs = array(
	'Category Brand Handlings'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrandHandling', 'url'=>array('create')),
	array('label'=>'Update CategoryBrandHandling', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandHandling', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrandHandling #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Brand',
			'value'=>$model->categoryBrand->name,
		),
		array(
			'label'=>'Handling',
			'value'=>$model->handling->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
