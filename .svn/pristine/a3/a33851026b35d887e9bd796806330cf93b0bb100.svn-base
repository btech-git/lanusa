<?php
$this->breadcrumbs = array(
	'Specifications'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Specification', 'url'=>array('create')),
	array('label'=>'View Specification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Specification', 'url'=>array('admin')),
);
?>

<h1>Update Specification <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>