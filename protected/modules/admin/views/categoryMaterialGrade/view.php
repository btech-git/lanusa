<?php
$this->breadcrumbs = array(
	'Category Material Grades'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGrade', 'url'=>array('create')),
	array('label'=>'Update CategoryMaterialGrade', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterialGrade', 'url'=>array('admin')),
);
?>

<h1>View CategoryMaterialGrade #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Material',
			'value'=>$model->categoryMaterial->name,
		),
		array(
			'label'=>'Grade',
			'value'=>$model->grade->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
