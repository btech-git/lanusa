<?php
$this->breadcrumbs = array(
    'Sale' => array('/transaction/sale/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $sale,
    'attributes' => array(
        array(
            'label' => 'Penjualan #',
            'value' => $sale->getCodeNumber(SaleHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $sale->date),
        ),
        array(
            'label' => 'Customer',
            'value' => CHtml::encode(CHtml::value($customer, 'company')),
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
            'value' => CHtml::encode(CHtml::value($branch, 'name')),
        ),
        array(
            'label' => 'Pembuat',
            'value' => CHtml::encode(CHtml::value($sale, 'admin.username')),
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

<table>
    <tr>
        <td style="width:80% ;text-align: right; font-weight: bold">Total Quantity</td>
        <td style="width:20% ;text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($sale, 'totalQuantity'))); ?>
        </td>
    </tr>
    <tr>
        <td style="width:80% ;text-align: right; font-weight: bold">Sub Total</td>
        <td style="width:20% ;text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($sale, 'subTotal'))); ?>
        </td>
    </tr>
    <?php if ($sale->is_non_tax === 0): ?>
        <tr>
            <td style="width:80% ;text-align: right; font-weight: bold">DPP lain-lain</td>
            <td style="width:20% ;text-align: right; font-weight: bold">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($sale, 'costOfGoodsSold'))); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td style="text-align: right; font-weight: bold">PPn <?php echo CHtml::encode(CHtml::value($sale, 'tax')); ?>%</td>
        <td style="text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($sale, 'calculatedTax'))); ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold">Ongkos Kirim</td>
        <td style="text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($sale, 'shipping_fee'))); ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold">Grand Total</td>
        <td style="text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($sale, 'grandTotal'))); ?>
        </td>
    </tr>
</table>

<div>
    <h4>List Pengiriman Barang</h4>
    <table>
        <thead>
            <tr>
                <th>Pengiriman #</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Item</th>
                <th>Ukuran</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Gudang</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sale->saleDetails as $saleDetail): ?>
                <?php foreach($saleDetail->deliveryDetails as $deliveryDetail): ?>
                    <tr>
                        <td><?php echo CHtml::encode($deliveryDetail->deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'deliveryHeader.date')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'deliveryHeader.note')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'product.name')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'product.size')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'quantity')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'product.unit.name')); ?></td>
                        <td><?php echo CHtml::encode(CHtml::value($deliveryDetail, 'warehouse.name')); ?></td>
                        <td><?php echo $deliveryDetail->is_inactive == 0 ? 'Active' : 'Inactive'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        
    </table>
</div>
<div id="link">
    <?php echo CHtml::link('Create', array('create')); ?>
    <?php echo CHtml::link('Manage', array('admin')); ?>
    <?php echo CHtml::link('Print', array('memo', 'id' => $sale->id), array('target' => '_blank')); ?>
    <?php echo CHtml::link('Print Persiapan', array('memoPicking', 'id' => $sale->id)); ?>
</div>