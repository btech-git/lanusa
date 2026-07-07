<?php
$this->breadcrumbs = array(
	'Account Category Types'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage AccountCategoryType', 'url'=>array('admin')),
);
?>

<h1>Create AccountCategoryType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>