<?php
$this->breadcrumbs=array(
	'Transfer'=>array('/transaction/transfer/create'),
	'View',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$transfer,
	'attributes'=>array(
		array(
			'label'=>'Transfer #',
			'value'=>$transfer->getCodeNumber(TransferHeader::CN_CONSTANT),
		),
		array(
			'label'=>'Tanggal',
			'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy",$transfer->date),
		),
		array(
			'label'=>'Gudang Asal',
			'value'=>$warehouseIdFrom->name,
		),
		array(
			'label'=>'Gudang Tujuan',
			'value'=>$warehouseIdTo->name,
		),
		array(
			'label'=>'Catatan',
			'value'=>$transfer->note,
		),
	),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'transfer-detail-grid',
	'dataProvider'=>$detailsDataProvider,
	'columns'=>array(
		'product.name: Nama Barang',
		'product.size: Ukuran',
		array(
			'header'=>'Jumlah',
			'value'=>'number_format($data->quantity, 0)',
			'htmlOptions'=>array(
				'style'=>'text-align: right',
			),
		),
		'product.unit.name: Satuan',
	),
)); ?>
<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
</div>