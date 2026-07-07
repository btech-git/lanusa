<?php
$this->breadcrumbs = array(
	'Branches'=>array('admin'),
	$model->name,
);

$this->menu = array(
	array('label'=>'Create Branch', 'url'=>array('create')),
	array('label'=>'Update Branch', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Branch', 'url'=>array('admin')),
);
?>

<h1>View Branch #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		'name',
		'address',
		'city',
		'province',
		'zip_code',
		'phone',
		'fax',
		'npwp',
                'bank_account',
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
