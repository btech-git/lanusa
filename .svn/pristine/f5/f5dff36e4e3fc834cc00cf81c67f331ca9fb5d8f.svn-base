<?php
$this->breadcrumbs = array(
	'Products'=>array('admin'),
	'Create',
);

$this->menu = array(
	array('label'=>'Manage Product', 'url'=>array('admin')),
);
?>

<h1>Create Product</h1>

<div id="form_div">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'specificationList'=>$specificationList,
		'listData'=>$listData,
		'action'=>CHtml::normalizeUrl(array('create')),
	)); ?>
</div>