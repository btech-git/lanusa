<?php
$this->breadcrumbs = array(
	'Category Material Grades'=>array('create'),
	'Manage',
);

$this->menu = array(
	array('label'=>'Create CategoryMaterialGrade', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-material-grade-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Category Material Grades</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-material-grade-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'category_material_id',
			'filter' => CHtml::listData(CategoryMaterial::model()->findAll(array(
				'with' => array(
					'category',
					'material'
				),
				'order' => 'category.name, material.name'
			)), 'id', 'name'),
			'value'=>'$data->categoryMaterial->name',
		),
		array(
			'name'=>'grade_id',
			'filter' => CHtml::listData(Grade::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value'=>'$data->grade->name',
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
