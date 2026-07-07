<?php
$this->breadcrumbs=array(
	'Admins'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Admin', 'url'=>array('admin')),
);
?>

<h1>Create Admin</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>