<?php
$this->breadcrumbs = array(
    'Indent' => array('/transaction/indent/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $indent,
    'attributes' => array(
        array(
            'label' => 'Indent #',
            'value' => $indent->getCodeNumber(IndentHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $indent->date),
        ),
        array(
            'label' => 'Customer',
            'value' => $customer->company,
        ),
        array(
            'label' => 'Catatan',
            'value' => $indent->note,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'indent-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        'product.name: Nama Barang',
        'product.size: Ukuran',
        array(
            'header' => 'Jumlah',
            'value' => 'number_format($data->quantity, 0)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'product.unit.name: Satuan',
        array(
            'header' => 'Harga Satuan',
            'value' => 'number_format($data->unit_price, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        array(
            'header' => 'Total',
            'value' => 'number_format($data->total, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
    ),
));
?>

<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
</div>