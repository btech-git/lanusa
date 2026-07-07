<h1>Kelola Penerimaan Giro Pembelian</h1>
<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'purchaseInvoice-grid',
    'dataProvider' => $dataProvider,
    'filter' => $purchaseCheque,
    'columns' => array(
        array(
            'name' => 'cn_ordinal',
            'header' => 'Pengeluaran Giro #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchaseCheque, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
						'<div style="display: inline-block"> &nbsp; /' . PurchaseCheque::CN_CONSTANT . '/ &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeDropDownList($purchaseCheque, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
						'<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeTextField($purchaseCheque, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(PurchaseCheque::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 200px'),
        ),
//        'issue_date',
//        'due_date',
//        'cheque_number',
//        'bank',
//        'amount',
        array(
            'header' => 'Supplier',
            'filter' => CHtml::dropDownList('SupplierId', $supplierId, CHtml::listData(Supplier::model()->findAll(), 'id', 'company'), array('empty' => '')),
            'value' => '$data->purchaseReceiptHeader->supplier->company',
        ),
        array(
            'name' => 'is_inactive',
            'filter' => array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive'),
            'value' => '$data->Status',
        ),
        array(
            'class' => 'CButtonColumn',
            'template'=>'{view}{update}{delete}',
        ),
    ),
));
?>
