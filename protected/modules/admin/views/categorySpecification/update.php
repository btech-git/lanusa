<?php
$this->breadcrumbs = array(
	'Category Specifications'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategorySpecification', 'url'=>array('create')),
	array('label'=>'View CategorySpecification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategorySpecification', 'url'=>array('admin')),
);
?>

<h1>Update CategorySpecification <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>