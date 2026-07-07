<?php
$this->breadcrumbs = array(
	'Connections'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Connection', 'url'=>array('create')),
	array('label'=>'View Connection', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Connection', 'url'=>array('admin')),
);
?>

<h1>Update Connection <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>