<?php
$this->breadcrumbs = array(
	'Bellows'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Bellow', 'url'=>array('create')),
	array('label'=>'View Bellow', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Bellow', 'url'=>array('admin')),
);
?>

<h1>Update Bellow <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>