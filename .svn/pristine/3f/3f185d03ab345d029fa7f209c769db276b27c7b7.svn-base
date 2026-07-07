<?php
$this->breadcrumbs = array(
	'Category Brand Discs'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrandDisc', 'url'=>array('create')),
	array('label'=>'Update CategoryBrandDisc', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandDisc', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrandDisc #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Brand',
			'value'=>$model->categoryBrand->name,
		),
		array(
			'label'=>'Disc Material',
			'value'=>$model->discMaterial->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
