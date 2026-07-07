<?php
$this->breadcrumbs = array(
	'Grades'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Grade', 'url'=>array('admin')),
);
?>

<h1>Create Grade</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>