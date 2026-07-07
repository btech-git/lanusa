<?php
$this->breadcrumbs = array(
    'Deposit' => array('/transaction/deposit/create'),
    'View',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<?php 
	$depsoitHeaderConstant;
	if($deposit->is_bank)
	{
		$depsoitHeaderConstant=DepositHeader::CN_CONSTANT_BANK;
	}else
	{
		$depsoitHeaderConstant=DepositHeader::CN_CONSTANT_CASH;
	}					
?>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $deposit,
    'attributes' => array(
        array(
            'label' => 'Pemasukan #',
            'value' => $deposit->getCodeNumber($depsoitHeaderConstant),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $deposit->date),
        ),
        array(
            'label' => 'Akun',
            'value' => $account->name,
        ),
        array(
            'label' => 'Catatan',
            'value' => $deposit->note,
        ),
        array(
            'label' => 'Branch',
            'value' => $branch->name,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $deposit->admin->username,
        ),
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'deposit-detail-grid',
    'dataProvider' => $detailsDataProvider,
    'columns' => array(
        'account.code: Kode Akun',
        'account.name: Nama Akun',
        array(
            'header' => 'Jumlah',
            'value' => 'number_format($data->amount, 2)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
        'memo: Memo',
    ),
));
?>

<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
	<?php echo CHtml::link('Manage', array('admin')); ?>
	<?php echo CHtml::link('Print', array('memo', 'id' => $deposit->id), array('target' => '_blank')); ?>
</div>