<?php
$this->breadcrumbs = array(
	'Category Grades'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryGrade', 'url'=>array('admin')),
);
?>

<h1>Create CategoryGrade</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>