<?php
$this->breadcrumbs = array(
	'Ranges'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Range', 'url'=>array('create')),
	array('label'=>'View Range', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Range', 'url'=>array('admin')),
);
?>

<h1>Update Range <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>