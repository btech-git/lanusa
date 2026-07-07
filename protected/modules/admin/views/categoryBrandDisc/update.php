<?php
$this->breadcrumbs = array(
	'Category Brand Discs'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'Create CategoryBrandDisc', 'url'=>array('create')),
	array('label'=>'View CategoryBrandDisc', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CategoryBrandDisc', 'url'=>array('admin')),
);
?>

<h1>Update CategoryBrandDisc <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>