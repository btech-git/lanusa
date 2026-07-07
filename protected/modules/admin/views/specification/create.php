<?php
$this->breadcrumbs = array(
	'Specifications'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Specification', 'url'=>array('admin')),
);
?>

<h1>Create Specification</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>