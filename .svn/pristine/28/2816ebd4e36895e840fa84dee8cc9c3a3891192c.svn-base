<?php
$this->pageTitle = Yii::app()->name . ' - Lanusa';
$this->breadcrumbs = array(
    'Lanusa',
);
?>
<h1>Transaksi</h1>

<div class="form">
    <?php if (Yii::app()->user->checkAccess('purchaseCreate') 
            || Yii::app()->user->checkAccess('purchaseReturnCreate') 
            || Yii::app()->user->checkAccess('salesReturnCreate') 
            || Yii::app()->user->checkAccess('saleCreate')
            || Yii::app()->user->checkAccess('deliveryCreate')
            || Yii::app()->user->checkAccess('receiveCreate')
    ): ?>
        <fieldset>
                <legend>Marketing</legend>
                <ul style="display: table-cell">
                        <?php if (Yii::app()->user->checkAccess('saleCreate')): ?>
                                <li><?php echo CHtml::link('Penjualan Barang', array('/transaction/sale/create')); ?><br/><br/></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->checkAccess('deliveryCreate')): ?>
                                <li><?php echo CHtml::link('Pengiriman Barang', array('/transaction/delivery/create')); ?><br/><br/></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->checkAccess('saleReturnCreate')): ?>
                                <li><?php echo CHtml::link('Retur Penjualan', array('/transaction/saleReturn/create')); ?><br/><br/></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->checkAccess('purchaseCreate')): ?>
                                <li><?php echo CHtml::link('Pembelian Barang', array('/transaction/purchase/create')); ?><br/><br/></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->checkAccess('receiveCreate')): ?>
                                <li><?php echo CHtml::link('Penerimaan Barang', array('/transaction/receive/create')); ?><br/><br/></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->checkAccess('purchaseReturnCreate')): ?>
                                <li><?php echo CHtml::link('Retur Pembelian', array('/transaction/purchaseReturn/create')); ?><br/><br/></li>
                        <?php endif; ?>
                </ul>
        </fieldset>
    <?php endif; ?>

    <?php if (
        Yii::app()->user->checkAccess('stockAdjustmentCreate') || 
        Yii::app()->user->checkAccess('stockTransferCreate') || 
        Yii::app()->user->checkAccess('saleEdit') || 
        Yii::app()->user->checkAccess('deliveryEdit') || 
        Yii::app()->user->checkAccess('pickingPrint')
    ): ?>
        <fieldset>
            <legend>Gudang</legend>
            <ul style="display: table-cell">
                <?php if (Yii::app()->user->checkAccess('stockAdjustmentCreate')): ?>
                    <li><?php echo CHtml::link('Penyesuaian Stok', array('/transaction/adjustment/create')); ?><br/><br/></li>
                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess('stockTransferCreate')): ?>
                    <li><?php echo CHtml::link('Transfer Stok', array('/transaction/transfer/create')); ?><br/><br/></li>
                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess('pickingPrint')): ?>
                    <li><?php echo CHtml::link('Print Persiapan', array('/transaction/sale/adminWarehouse')); ?><br/><br/></li>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endif; ?>
</div>
