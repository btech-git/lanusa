<?php
$this->breadcrumbs = array(
	'Handlings'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Handling', 'url'=>array('create')),
	array('label'=>'View Handling', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Handling', 'url'=>array('admin')),
);
?>

<h1>Update Handling <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>