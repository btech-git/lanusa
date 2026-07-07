<?php
$this->breadcrumbs = array(
    'Journal Voucher' => array('/transaction/journalVoucher/create'),
    'View',
);
?>
<h1><?php echo 'Jurnal Umum / ' . $this->action->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $journal,
    'attributes' => array(
        array(
            'label' => 'Jurnal #',
            'value' => $journal->getCodeNumber(JournalVoucherHeader::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $journal->date),
        ),
        array(
            'label' => 'Catatan',
            'value' => $journal->note,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $admin->username,
        ),
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'journal-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        'account.code: Kode Akun',
        'account.name: Nama Akun',
        array(
            'header' => 'Debit',
            'value' => 'number_format($data->debit, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        array(
            'header' => 'Credit',
            'value' => 'number_format($data->credit, 2)',
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
	<?php echo CHtml::link('Update', array('update', 'id' => $journal->id)); ?>
	<?php echo CHtml::link('Print', array('memo', 'id' => $journal->id)); ?>
</div>