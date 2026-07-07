<?php
$this->breadcrumbs = array(
	'Category Material Grade Brands'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGradeBrand', 'url'=>array('create')),
	array('label'=>'View CategoryMaterialGradeBrand', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterialGradeBrand', 'url'=>array('admin')),
);
?>

<h1>Update CategoryMaterialGradeBrand <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>