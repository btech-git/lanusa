<?php
$this->breadcrumbs = array(
	'Category Materials'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryMaterial', 'url'=>array('create')),
	array('label'=>'View CategoryMaterial', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterial', 'url'=>array('admin')),
);
?>

<h1>Update CategoryMaterial <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>