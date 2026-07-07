<?php
$this->breadcrumbs = array(
	'Suppliers'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Supplier', 'url'=>array('create')),
	array('label'=>'Update Supplier', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Supplier', 'url'=>array('admin')),
);
?>

<h1>View Supplier #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'company',
		'name',
		'address',
		'phone',
		'fax',
		'email',
		'website',
		'note',
		array(
			'label'=>'Account',
			'value'=>($model->account === null) ? '' : $model->account->name,
		),
		array(
			'label'=>'Branch',
			'value'=>($model->branch === null) ? '' : $model->branch->name,
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
