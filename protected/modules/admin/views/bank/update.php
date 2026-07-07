<?php
$this->breadcrumbs = array(
	'Banks'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Bank', 'url'=>array('create')),
	array('label'=>'View Bank', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Bank', 'url'=>array('admin')),
);
?>

<h1>Update Bank <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>