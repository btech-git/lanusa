<?php
$this->breadcrumbs = array(
	'Varieties'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Variety', 'url'=>array('create')),
	array('label'=>'View Variety', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Variety', 'url'=>array('admin')),
);
?>

<h1>Update Variety <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>