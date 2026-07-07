<?php
$this->breadcrumbs = array(
    'Sale Cheque' => array('/transaction/saleCheque/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $saleCheque,
    'attributes' => array(
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
		array(
            'label' => 'Customer',
            'value' => $saleCheque->customer->company,
        ),
        array(
            'label' => 'Giro #',
            'value' => $saleCheque->getCodeNumber(SaleCheque::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal Terima',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $saleCheque->receive_date),
        ),
        array(
            'label' => 'Tanggal Jatuh Tempo',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $saleCheque->due_date),
        ),
        array(
            'label' => 'Catatan',
            'value' => $saleCheque->note,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $saleCheque->admin->username,
        ),
    ),
));
?>

<br />

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sale-cheque-detail-grid',
	'dataProvider'=>$detailsDataProvider,
	'columns'=>array(
		array(
			'header'=>'Tanda Terima#',
			'value'=>'$data->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)',
		),
		array(
			'header' => 'Tanggal',
			'name' => 'date',
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->saleReceiptHeader->date)'
		),
		array(
			'header' => 'Jatuh Tempo',
			'name' => 'due_date',
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->saleReceiptHeader->due_date)'
		),
		'saleReceiptHeader.customer.company: Customer',
		array(
			'header'=>'Total(Rp)',
			'value'=>'number_format($data->saleReceiptHeader->totalInvoice, 2)',
			'htmlOptions'=>array(
				'style'=>'text-align: right',
			),
		),
		'bank',
		'cheque_number',
		array(
			'header'=>'Amount',
			'value'=>'number_format($data->amount, 2)',
			'htmlOptions'=>array(
				'style'=>'text-align: right',
			),
		),
	),
)); ?>

<div>
	<?php echo 'Total Amount: '. CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleCheque, 'totalAmount'))); ?>
</div>

<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
    <?php echo CHtml::link('Print Cheque Order', array('memo', 'id' => $saleCheque->id), array('target' => '_blank')); ?>
</div>