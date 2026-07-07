<?php
$this->breadcrumbs = array(
	'Category Classifications'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage CategoryClassification', 'url'=>array('admin')),
);
?>

<h1>Create CategoryClassification</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>