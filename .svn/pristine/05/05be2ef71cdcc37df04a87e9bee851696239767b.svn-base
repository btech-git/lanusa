<?php
$this->breadcrumbs=array(
	'Products'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'Update Product', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Product', 'url'=>array('admin')),
);
?>

<h1>View Product #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		'name',
		'buying_price',
		'selling_price',
		array(
			'label'=>'Category',
			'name'=>'category.name',
		),
		array(
			'label'=>'Unit',
			'name'=>'unit.name',
		),
		'size',
		array(
			'label'=>'Panjang',
			'name'=>'length',
			'visible'=>in_array('length', $specificationList),
		),
		array(
			'label'=>'Class Tebal',
			'name'=>'classification.name',
			'visible'=>in_array('classification_id', $specificationList),
		),
		array(
			'label'=>'Material',
			'name'=>'material.name',
			'visible'=>in_array('material_id', $specificationList),
		),
		array(
			'label'=>'Grade',
			'name'=>'grade.name',
			'visible'=>in_array('grade_id', $specificationList),
		),
		array(
			'label'=>'Merk',
			'name'=>'brand.name',
			'visible'=>in_array('brand_id', $specificationList),
		),
		array(
			'label'=>'Ketebalan',
			'name'=>'thickness.name',
			'visible'=>in_array('thickness_id', $specificationList),
		),
		array(
			'label'=>'Body',
			'name'=>'bodyMaterial.name',
			'visible'=>in_array('body_material_id', $specificationList),
		),
		array(
			'label'=>'Connection',
			'name'=>'connection.name',
			'visible'=>in_array('connection_id', $specificationList),
		),
		array(
			'label'=>'Disc',
			'name'=>'discMaterial.name',
			'visible'=>in_array('disc_material_id', $specificationList),
		),
		array(
			'label'=>'Handling',
			'name'=>'handling.name',
			'visible'=>in_array('handling_id', $specificationList),
		),
		array(
			'label'=>'Tipe',
			'name'=>'type.name',
			'visible'=>in_array('type_id', $specificationList),
		),
		array(
			'label'=>'Jenis',
			'name'=>'variety.name',
			'visible'=>in_array('variety_id', $specificationList),
		),
		array(
			'label'=>'Drat / ND',
			'name'=>'drat',
			'visible'=>in_array('drat', $specificationList),
		),
		array(
			'label'=>'Tebal Fisik',
			'name'=>'physical_thickness',
			'visible'=>in_array('physical_thickness', $specificationList),
		),
		array(
			'label'=>'Connection',
			'name'=>'connectionMaterial.name',
			'visible'=>in_array('connection_material_id', $specificationList),
		),
		array(
			'label'=>'Parameter',
			'name'=>'parameter.name',
			'visible'=>in_array('parameter_id', $specificationList),
		),
		array(
			'label'=>'Range',
			'name'=>'range.name',
			'visible'=>in_array('range_id', $specificationList),
		),
		array(
			'label'=>'Bellow',
			'name'=>'bellow.name',
			'visible'=>in_array('bellow_id', $specificationList),
		),
		array(
			'label'=>'Connection Diameter',
			'name'=>'connection_diameter',
			'visible'=>in_array('connection_diameter', $specificationList),
		),
		array(
			'label'=>'Status',
			'value'=>$model->status,
		),
	),
)); ?>
