<?php
$this->breadcrumbs = array(
	'Category Specifications'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategorySpecification', 'url'=>array('admin')),
);
?>

<h1>Create CategorySpecification</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>