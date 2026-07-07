<?php
$this->breadcrumbs = array(
	'Category Varieties'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryVariety', 'url'=>array('admin')),
);
?>

<h1>Create CategoryVariety</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>