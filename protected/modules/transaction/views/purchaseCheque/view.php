<?php
$this->breadcrumbs = array(
    'Purchase Cheque' => array('/transaction/purchaseCheque/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $purchaseCheque,
    'attributes' => array(
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
        array(
            'label' => 'Pengeluaran giro #',
            'value' => $purchaseCheque->getCodeNumber(PurchaseCheque::CN_CONSTANT),
        ),
        array(
            'label' => 'Issue Date',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $purchaseCheque->issue_date),
        ),
        array(
            'label' => 'Due Date',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $purchaseCheque->due_date),
        ),
		array(
            'label' => 'Supplier',
            'value' => $purchaseReceiptHeader->supplier->company,
        ),
		array(
            'label' => 'Faktur Pembelian #',
            'value' => $purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Bank',
            'value' => $purchaseCheque->account->name,
        ),
        array(
            'label' => 'Nomor Giro #',
            'value' => $purchaseCheque->cheque_number,
        ),
        array(
            'label' => 'Jumlah (Rp)',
            'value' => number_format($purchaseCheque->amount, 2),
        ),
        array(
            'label' => 'Catatan',
            'value' => $purchaseCheque->note,
        ),
        
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'purchaseReceipt-detail-grid',
    'dataProvider' => new CArrayDataProvider($purchaseCheque->purchaseReceiptHeader->purchaseReceiptDetails),
    'columns' => array(
        array(
			'name' => 'cn_ordinal',
			'header' => 'Invoice Pembelian #',
			'value' => '$data->purchaseInvoiceHeader->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)',
			'htmlOptions' => array('style' => 'width: 200px'),
		),
		array(
			'header' => 'Tanggal',
			'name' => 'date',
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->purchaseInvoiceHeader->date)'
		),
        'purchaseInvoiceHeader.purchaseHeader.supplier.company: Supplier',
        array(
            'header' => 'Total',
            'value' => 'number_format($data->purchaseInvoiceHeader->totalPurchase, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'memo: Memo',
    ),
));
?>

<br/>

<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
	<?php echo CHtml::link('Print', array('memo', 'id' => $purchaseCheque->id), array('target' => '_blank')); ?>
</div>