<?php
$this->breadcrumbs = array(
	'Boards'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create Board', 'url'=>array('create')),
	array('label'=>'View Board', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Board', 'url'=>array('admin')),
);
?>

<h1>Update Board <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>