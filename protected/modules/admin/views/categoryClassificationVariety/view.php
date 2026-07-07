<?php
$this->breadcrumbs = array(
	'Category Classification Varieties'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryClassificationVariety', 'url'=>array('create')),
	array('label'=>'Update CategoryClassificationVariety', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryClassificationVariety', 'url'=>array('admin')),
);
?>

<h1>View CategoryClassificationVariety #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Classfication',
			'value'=>$model->categoryClassification->name,
		),
		array(
			'label'=>'Variety',
			'value'=>$model->variety->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
