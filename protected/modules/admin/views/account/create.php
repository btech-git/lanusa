<?php
$this->breadcrumbs = array(
	'Accounts'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Account', 'url'=>array('admin')),
);
?>

<h1>Create Account</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>