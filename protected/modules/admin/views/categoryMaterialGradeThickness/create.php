<?php
$this->breadcrumbs = array(
	'Category Material Grade Thicknesses'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryMaterialGradeThickness', 'url'=>array('admin')),
);
?>

<h1>Create CategoryMaterialGradeThickness</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>