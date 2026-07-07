<?php
$this->breadcrumbs = array(
	'Category Brand Connections'=>array('admin'),
	$model->id,
);

$this->menu = array(
	array('label'=>'Create CategoryBrandConnection', 'url'=>array('create')),
	array('label'=>'Update CategoryBrandConnection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandConnection', 'url'=>array('admin')),
);
?>

<h1>View CategoryBrandConnection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'label'=>'Category Brand',
			'value'=>$model->categoryBrand->name,
		),
		array(
			'label'=>'Connection',
			'value'=>$model->connection->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
