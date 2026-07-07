<h1>Kelola Penerimaan Faktur Pembelian</h1>
<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purchaseInvoice-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$purchaseInvoice,
	'columns'=>array(
		 array(
			'name' => 'cn_ordinal',
			'header' => 'Penerimaan Faktur #',
			'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchaseInvoice, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
						'<div style="display: inline-block"> &nbsp; /' . PurchaseInvoiceHeader::CN_CONSTANT . '/ &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeDropDownList($purchaseInvoice, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
						'<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeTextField($purchaseInvoice, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
			'value' => '$data->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)',
			'htmlOptions' => array('style' => 'width: 200px'),
		),
		array(
			'header' => 'Tanggal',
			'name' => 'date',
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
		),
		'reference',
		array(
			'header' => 'Supplier',
			'filter' => CHtml::dropDownList('SupplierId', $supplierId, CHtml::listData(Supplier::model()->findAll(), 'id', 'company'), array('empty'=>'')),
			'value' => 'CHtml::encode(CHtml::value($data, "supplier.company"))',
		),
		array(
			'name'=>'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE=>'Active', ActiveRecord::INACTIVE=>'Inactive'),
			'value'=>'$data->status',
		),
		array(
			'class'=>'CButtonColumn',
			'updateButtonUrl'=>'CHtml::normalizeUrl(array("update", "id"=>$data->id))',
		),
	),
)); ?>
