<?php
$this->breadcrumbs = array(
    'Fee Invoices' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List FeeInvoice', 'url' => array('index')),
    array('label' => 'Create FeeInvoice', 'url' => array('create')),
    array('label' => 'Update FeeInvoice', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete FeeInvoice', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage FeeInvoice', 'url' => array('admin')),
);
?>

<h1>View Invoice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'label' => 'Invoice #',
            'value' => $model->getCodeNumber(FeeInvoice::CN_CONSTANT),
        ),
        array(
            'label' => 'Tanggal',
            'value'=>Yii::app()->dateFormatter->format("d MMMM yyyy", $model->date),
        ),
        array(
            'label' => 'Customer',
            'value' => $model->customer->company,
        ),
        array(
            'label' => 'Branch',
            'value' => $model->branch->name,
        ),
        array(
            'label' => 'note',
            'value' => $model->note,
        ),
        array(
            'label' => 'Pembuat',
            'value' => $model->admin->username,
        ),
        array(
            'label' => 'Status',
            'value' => $model->is_inactive == 0 ? 'Active' : 'Inactive',
        ),
    ),
)); ?>

<hr />

<div>
    <table style="border: 1px solid">
        <tr>
            <td style="font-weight: bold; text-align: center">Bank Penerima</td>
            <td style="font-weight: bold; text-align: center">Jumlah (Rp)</td>
            <td style="font-weight: bold; text-align: center" colspan="2">PPn</td>
            <td style="font-weight: bold; text-align: center" colspan="2">PPh 23</td>
        </tr>
        <tr>
            <td><?php echo CHtml::encode(CHtml::value($model, 'account.name')); ?></td>
            <td><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($model, 'fee_amount'))); ?></td>
            <td><?php echo CHtml::encode(CHtml::value($model, 'tax_item_value')); ?>%</td>
            <td><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($model, 'tax_item_amount'))); ?></td>
            <td><?php echo CHtml::encode(CHtml::value($model, 'tax_service_value')); ?>%</td>
            <td><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($model, 'tax_service_amount'))); ?></td>
        </tr>
    </table>
</div>

<div id="link">
    <?php echo CHtml::link('Create', array('create')); ?>
    <?php echo CHtml::link('Manage', array('admin')); ?>
    <?php echo CHtml::link('Print', array('memo', 'id' => $model->id)); ?>
</div>