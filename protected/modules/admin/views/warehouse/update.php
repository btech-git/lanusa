<?php
$this->breadcrumbs = array(
	'Warehouses'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Warehouse', 'url'=>array('create')),
	array('label'=>'View Warehouse', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Warehouse', 'url'=>array('admin')),
);
?>

<h1>Update Warehouse <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>