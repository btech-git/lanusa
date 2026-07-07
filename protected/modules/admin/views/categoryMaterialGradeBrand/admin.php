<?php
$this->breadcrumbs = array(
	'Category Material Grade Brands'=>array('create'),
	'Manage',
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGradeBrand', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-material-grade-brand-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Category Material Grade Brands</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-material-grade-brand-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'category_material_grade_id',
			'filter' => CHtml::listData(CategoryMaterialGrade::model()->findAll(array(
				'with' => array('categoryMaterial' => array(
					'with' => array(
						'category',
						'material')
					), 'grade'
				),
				'order' => 'category.name, material.name, grade.name'
			)), 'id', 'name'),
			'value'=>'CHtml::encode(CHtml::value($data, "categoryMaterialGrade.name"))',
		),
		array(
			'name'=>'brand_id',
			'filter' => CHtml::listData(Brand::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value'=>'$data->brand->name',
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
