<?php
$this->breadcrumbs = array(
    'Down Payment' => array('/transaction/saleDownpayment/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $saleDownpayment,
    'attributes' => array(
        array(
            'label' => 'Uang Muka #',
            'value' => $saleDownpayment->getCodeNumber(SaleDownpayment::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $saleDownpayment->date),
        ),
        array(
            'label' => 'Customer',
            'value' => $customer->company,
        ),
        array(
            'label' => 'Board',
            'value' => $board->name,
        ),
        array(
            'label' => 'Akun',
            'value' => $account->name,
        ),
		 array(
            'label' => 'Quantity',
            'value' => number_format($saleDownpayment->quantity, 0),
        ),
        array(
            'label' => 'Jumlah (Rp)',
            'value' => number_format($saleDownpayment->amount, 2),
        ),
        array(
            'label' => 'Pajak (%)',
            'value' => number_format($saleDownpayment->tax, 0),
        ),
		array(
            'label' => 'Pajak (%)',
            'value' => number_format($saleDownpayment->tax, 0),
        ),
        array(
            'label' => 'Nomor Pajak',
            'value' => $saleDownpayment->tax_number,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
    ),
));
?>
<br />
<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
	<?php echo CHtml::link('Print', array('memo', 'id' => $saleDownpayment->id), array('target' => '_blank')); ?>
	<?php echo CHtml::link('Print Faktur Pajak', array('taxform', 'id' => $saleDownpayment->id), array('target' => '_blank')); ?>
</div>