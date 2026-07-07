<?php
$this->breadcrumbs = array(
	'Category Connections'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryConnection', 'url'=>array('create')),
	array('label'=>'View CategoryConnection', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryConnection', 'url'=>array('admin')),
);
?>

<h1>Update CategoryConnection <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>