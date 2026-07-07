<?php
$this->breadcrumbs = array(
	'Category Material Grade Thicknesses'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGradeThickness', 'url'=>array('create')),
	array('label'=>'Update CategoryMaterialGradeThickness', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterialGradeThickness', 'url'=>array('admin')),
);
?>

<h1>View CategoryMaterialGradeThickness #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Material Grade',
			'value'=>$model->categoryMaterialGrade->name,
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
