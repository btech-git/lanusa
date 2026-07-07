<?php
$this->breadcrumbs = array(
	'Customers'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'Update Customer', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>View Customer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'company',
		'name',
		'address',
		'phone',
		'fax',
		'npwp',
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
