<?php
$this->breadcrumbs = array(
	'Category Material Grade Thicknesses'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGradeThickness', 'url'=>array('create')),
	array('label'=>'View CategoryMaterialGradeThickness', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryMaterialGradeThickness', 'url'=>array('admin')),
);
?>

<h1>Update CategoryMaterialGradeThickness <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>