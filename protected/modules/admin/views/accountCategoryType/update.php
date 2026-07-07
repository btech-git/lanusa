<?php
$this->breadcrumbs = array(
	'Account Category Types'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create AccountCategoryType', 'url'=>array('create')),
	array('label'=>'View AccountCategoryType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccountCategoryType', 'url'=>array('admin')),
);
?>

<h1>Update AccountCategoryType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>