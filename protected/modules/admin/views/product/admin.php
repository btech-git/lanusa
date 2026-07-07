<?php
$this->breadcrumbs = array(
	'Products' => array('create'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create Product', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Products</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>

<div class="search-form" style="display:none">
	<div id="search_div">
		<?php
		$this->renderPartial('//site/_search', array(
			'model' => $model,
			'specificationList' => array(),
			'listData' => $this->listData(),
			'action' => CHtml::normalizeUrl(array('admin')),
		));
		?>
	</div>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'product-grid',
	'dataProvider' => $dataProvider,
	'filter' => $model,
	'columns' => array(
		array(
			'name' => 'category_id',
			'filter' => CHtml::listData(Category::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value' => 'CHtml::value($data, "category.name")',
		),
		'code',
		'name',
		'size',
		/*
		  'lever',
		  'gear',
		  'seat_material',
		  'rising_stern',
		  'drat',
		  'type_id',
		  'brand_id',
		  'material_id',
		  'disc_material_id',
		  'body_type_id',
		  'connection_id',
		  'grade_id',
		  'hub_id',
		  'class_id',
		  'thickness_id',
		  'is_inactive',
		 */
		array(
			'name' => 'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive'),
			'value' => '$data->status',
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{view},{update}',
		),
	),
));
?>
