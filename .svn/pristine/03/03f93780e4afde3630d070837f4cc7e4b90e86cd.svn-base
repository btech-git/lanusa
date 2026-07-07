<?php
$this->breadcrumbs = array(
    'Sale Payment' => array('/transaction/salePayment/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $salePayment,
    'attributes' => array(
        array(
            'label' => 'Pelunasan #',
            'value' => $salePayment->getCodeNumber(SalePaymentHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value' => Yii::app()->dateFormatter->format("d MMMM yyyy", $salePayment->date),
        ),
        array(
            'label' => 'Customer',
            'value' => $saleReceiptHeader->customer->company,
        ),
        array(
            'label' => 'Tanda Terima #',
            'value' => $saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal Tanda Terima',
            'value' => $saleReceiptHeader->date,
        ),
        array(
            'label' => 'Catatan',
            'value' => $salePayment->note,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $salePayment->admin->username,
        ),
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'purchase-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        'account.name: Nama Akun',
        'paymentType.name: Jenis Pembayaran',
        array(
            'header' => 'Jumlah',
            'value' => 'number_format($data->amount, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'memo',
    ),
));
?>

<div id="link">
    <?php echo CHtml::link('Create', array('create')); ?>
    <?php echo CHtml::link('Manage', array('admin')); ?>
    <?php echo CHtml::link('Print', array('memo', 'id' => $salePayment->id)); ?>
</div>