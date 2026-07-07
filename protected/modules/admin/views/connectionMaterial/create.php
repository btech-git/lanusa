<?php
$this->breadcrumbs = array(
	'Connection Materials'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage ConnectionMaterial', 'url'=>array('admin')),
);
?>

<h1>Create ConnectionMaterial</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>