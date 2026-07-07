<?php
$this->breadcrumbs = array(
	'Category Brand Discs'=>array('create'),
	'Manage',
);

$this->menu = array(
	array('label'=>'Create CategoryBrandDisc', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-brand-disc-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Category Brand Discs</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-brand-disc-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'category_brand_id',
			'filter' => CHtml::listData(CategoryBrand::model()->findAll(array(
				'with' => array(
					'category',
					'brand'
				),
				'order' => 'category.name, brand.name'
			)), 'id', 'name'),
			'value'=>'$data->categoryBrand->name',
		),
		array(
			'name'=>'disc_material_id',
			'filter' => CHtml::listData(DiscMaterial::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value'=>'$data->discMaterial->name',
		),
		array(
			'name'=>'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE=>'Active', ActiveRecord::INACTIVE=>'Inactive'),
			'value'=>'$data->status',
		),
		array(
			'class'=>'CButtonColumn',
				'template'=>'{view},{update}',
		),
	),
)); ?>
