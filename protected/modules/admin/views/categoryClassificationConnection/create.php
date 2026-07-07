<?php
$this->breadcrumbs = array(
	'Category Classification Connections'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryClassificationConnection', 'url'=>array('admin')),
);
?>

<h1>Create CategoryClassificationConnection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>