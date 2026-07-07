<?php
$this->breadcrumbs = array(
    'Sale',
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $sale,
    'attributes' => array(
        array(
            'label' => 'Pengiriman #',
            'value' => $sale->getCodeNumber(SaleHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $sale->date),
        ),
        array(
            'label' => 'Customer',
            'value' => $customer->company,
        ),
		array(
            'label' => 'Customer PO#',
            'value' => $sale->reference,
        ),
        array(
            'label' => 'Catatan',
            'value' => $sale->note,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
    ),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'delivery-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        'product_name: Nama Barang',
        'product.size: Ukuran',
        array(
            'header' => 'Jumlah',
            'value' => 'number_format($data->quantity, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'unit.name: Satuan',
        array(
            'header' => 'Harga Satuan',
            'value' => 'number_format($data->unit_price, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'discount: Diskon (%)',
        array(
            'header' => 'Total',
            'value' => 'number_format($data->total, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
    ),
)); ?>

<div id="link">
    <?php echo CHtml::link('Manage', array('adminWarehouse')); ?>
    <?php echo CHtml::link('Print Persiapan', array('memoPicking', 'id' => $sale->id)); ?>
</div>