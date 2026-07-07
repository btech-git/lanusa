<?php
$this->breadcrumbs = array(
	'Parameters'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Parameter', 'url'=>array('admin')),
);
?>

<h1>Create Parameter</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>