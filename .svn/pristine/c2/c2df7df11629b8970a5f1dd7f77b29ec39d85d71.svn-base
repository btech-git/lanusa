<?php
$this->breadcrumbs = array(
	'Classifications'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Classification', 'url'=>array('create')),
	array('label'=>'View Classification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Classification', 'url'=>array('admin')),
);
?>

<h1>Update Classification <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>