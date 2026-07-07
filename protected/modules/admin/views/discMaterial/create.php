<?php
$this->breadcrumbs = array(
	'Disc Materials'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage DiscMaterial', 'url'=>array('admin')),
);
?>

<h1>Create DiscMaterial</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>