<?php
$this->breadcrumbs = array(
	'Category Material Grade Brands'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGradeBrand', 'url'=>array('create')),
	array('label'=>'Update CategoryMaterialGradeBrand', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterialGradeBrand', 'url'=>array('admin')),
);
?>

<h1>View CategoryMaterialGradeBrand #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Material grade',
			'value'=>$model->categoryMaterialGrade->name,
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
