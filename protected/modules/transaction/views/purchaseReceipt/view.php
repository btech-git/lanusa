<?php
$this->breadcrumbs = array(
    'PurchaseReceipt' => array('/transaction/purchaseReceipt/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $purchaseReceipt,
    'attributes' => array(
        array(
            'label' => 'Tanda Terima Pembelian #',
            'value' => $purchaseReceipt->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $purchaseReceipt->date),
        ),
        array(
            'label' => 'Supplier',
            'value' => $supplier->company,
        ),
        array(
            'label' => 'Catatan',
            'value' => $purchaseReceipt->note,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $purchaseReceipt->admin->username,
        ),
    ),
));
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'purchaseReceipt-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        array(
			'name' => 'cn_ordinal',
			'header' => 'Receive Pembelian #',
			'value' => '$data->receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT)',
			'htmlOptions' => array('style' => 'width: 200px'),
		),
		array(
			'header' => 'Tanggal',
			'name' => 'date',
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->receiveHeader->date)'
		),
        array(
            'header' => 'Total',
            'value' => 'number_format($data->receiveHeader->grandTotalReceipt, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'memo: Memo',
    ),
)); ?>

<div>
    <table>
        <tr>
            <td style="font-weight: bold">Grand Total</td>
            <td style="font-weight: bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchaseReceipt, 'totalPurchase'))); ?></td>
        </tr>
    </table>
</div>

<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
    <?php echo CHtml::link('Print', array('memo', 'id' => $purchaseReceipt->id), array('target' => '_blank')); ?>
</div>