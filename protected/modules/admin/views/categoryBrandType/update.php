<?php
$this->breadcrumbs = array(
	'Category Brand Types'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryBrandType', 'url'=>array('create')),
	array('label'=>'View CategoryBrandType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandType', 'url'=>array('admin')),
);
?>

<h1>Update CategoryBrandType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>