<?php
$this->breadcrumbs = array(
	'Category Grades'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryGrade', 'url'=>array('create')),
	array('label'=>'Update CategoryGrade', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryGrade', 'url'=>array('admin')),
);
?>

<h1>View CategoryGrade #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category',
			'value'=>$model->category->name,
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
