<?php
$this->breadcrumbs = array(
	'Category Brand Types'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryBrandType', 'url'=>array('admin')),
);
?>

<h1>Create CategoryBrandType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>