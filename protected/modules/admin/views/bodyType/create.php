<?php
$this->breadcrumbs = array(
	'Body Types'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage BodyType', 'url'=>array('admin')),
);
?>

<h1>Create BodyType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>