<?php
$this->breadcrumbs = array(
	'Customers'=>array('create'),
	'Manage',
);

$this->menu = array(
	array('label'=>'Create Customer', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('customer-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Customers</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'company',
		'name',
		'address',
		'phone',
		array(
			'name' => 'branch_id',
			'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value' => 'CHtml::value($data, "branch.name")',
		),
		array(
			'name' => 'account_id',
			'filter' => CHtml::listData(Account::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value' => 'CHtml::value($data, "account.name")',
		),
		array(
			'name'=>'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE=>'Active', ActiveRecord::INACTIVE=>'Inactive'),
			'value'=>'$data->status',
		),
		/*
		'npwp',
		'email',
		'website',
		'note',
		'account_id',
		'journal_downpayment_id',
		'is_inactive',
		*/
		array(
			'class'=>'CButtonColumn',
				'template'=>'{view},{update}',
		),
	),
)); ?>
