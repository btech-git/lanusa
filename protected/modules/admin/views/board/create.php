<?php
$this->breadcrumbs = array(
	'Boards'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Board', 'url'=>array('admin')),
);
?>

<h1>Create Board</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>