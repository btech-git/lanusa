<?php
$this->breadcrumbs = array(
	'Category Brand Connections'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryBrandConnection', 'url'=>array('admin')),
);
?>

<h1>Create CategoryBrandConnection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>