<?php
$this->breadcrumbs = array(
	'Connections'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Connection', 'url'=>array('admin')),
);
?>

<h1>Create Connection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>