<?php
$this->breadcrumbs = array(
	'Classifications'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Classification', 'url'=>array('admin')),
);
?>

<h1>Create Classification</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>