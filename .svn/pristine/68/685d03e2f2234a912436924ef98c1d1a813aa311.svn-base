<?php
$this->pageTitle = Yii::app()->name . ' - Lanusa';
$this->breadcrumbs = array(
    'Lanusa',
);
Yii::app()->clientScript->registerScript('transaction', "
    if (!document.getElementById('purchase-ul').getElementsByTagName('li').length)
        document.getElementById('purchase-fieldset').style.display = 'none';
    if (!document.getElementById('warehouse-ul').getElementsByTagName('li').length)
        document.getElementById('warehouse-fieldset').style.display = 'none';
    if (!document.getElementById('sales-ul').getElementsByTagName('li').length)
        document.getElementById('sales-fieldset').style.display = 'none';
    if (!document.getElementById('accounting-ul').getElementsByTagName('li').length)
        document.getElementById('accounting-fieldset').style.display = 'none';
");
?>
<h1>Laporan Transaksi</h1>

<?php //$this->renderPartial('/site/_mode', array('view' => 'report')); ?>

<div class="form">    
    <?php if (Yii::app()->user->checkAccess('purchaseReport') 
		|| Yii::app()->user->checkAccess('saleReport')
	): ?>
        <fieldset id="purchase-fieldset">
            <legend>Marketing</legend>
            <ul id="purchase-ul" style="display: table-cell">
                <?php if (Yii::app()->user->checkAccess('purchaseReport')): ?>
                    <li><?php echo CHtml::link('Pembelian Barang', array('/report/purchase/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Pembelian per Item (Summary)', array('/report/purchaseItems/summary')); ?><br/><br/></li>
					<li><?php echo CHtml::link('Order Pembelian (Detail PO & Receive)', array('/report/purchaseRecap/summary')); ?><br/><br/></li>
                <?php endif; ?> 	
                <?php if (Yii::app()->user->checkAccess('saleReport')): ?>
                    <li><?php echo CHtml::link('Penjualan Barang', array('/report/sale/summary')); ?><br/><br/></li>
<!--                    <li><?php //echo CHtml::link('Penjualan Barang (Summary)', array('/report/saleRecap/summary')); ?><br/><br/></li>-->
                    <li><?php echo CHtml::link('Penjualan per Item (Summary)', array('/report/saleItem/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Order Penjualan (Detail SJ & Invoice)', array('/report/saleDeliveryInvoice/summary')); ?><br/><br/></li>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endif; ?>

    <?php if (Yii::app()->user->checkAccess('receiveReport') 
		|| Yii::app()->user->checkAccess('purchaseReturnReport') 
		|| Yii::app()->user->checkAccess('stockAdjustmentReport') 
		|| Yii::app()->user->checkAccess('stockTransferReport') 
		|| Yii::app()->user->checkAccess('stockReport') 
		|| Yii::app()->user->checkAccess('deliveryReport') 
		|| Yii::app()->user->checkAccess('saleReturnReport')
	): ?>
        <fieldset id="warehouse-fieldset">
            <legend>Gudang</legend>
            <ul id="warehouse-ul" style="display: table-cell">
                <?php if (Yii::app()->user->checkAccess('receiveReport')): ?>
                    <li><?php echo CHtml::link('Penerimaan Barang', array('/report/receive/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Penerimaan per Item (Summary)', array('/report/receiveItem/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Penerimaan Barang Belum Tanda Terima', array('/report/receiveOutstandingReceipt/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('purchaseReturnReport')): ?>
                    <li> <?php echo CHtml::link('Retur Pembelian', array('/report/purchaseReturn/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('deliveryReport')): ?>
                    <li><?php echo CHtml::link('Pengiriman Barang', array('/report/delivery/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Pengiriman per Item', array('/report/deliveryItem/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Pengiriman Belum Invoice', array('/report/deliveryOutstandingInvoice/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleReturnReport')): ?>
                    <li> <?php echo CHtml::link('Retur Penjualan', array('/report/saleReturn/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('stockAdjustmentReport')): ?>
                    <li><?php echo CHtml::link('Stok Adjustment', array('/report/adjustment/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('stockTransferReport')): ?>
                    <li><?php echo CHtml::link('Stok Transfer', array('/report/transfer/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('stockReport')): ?>
                    <li><?php echo CHtml::link('Stok per Gudang', array('/report/stockLocal/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Stok Gudang Global', array('/report/stockGlobal/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Stok Gudang Summary', array('/report/stock/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Stok', array('/report/inventory/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Stok per Cabang', array('/report/inventoryPerBranch/summary')); ?><br/><br/></li>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endif; ?>

    <?php
    if (
            Yii::app()->user->checkAccess('purchaseReceiptReport') ||
            Yii::app()->user->checkAccess('purchasePaymentReport') ||
            Yii::app()->user->checkAccess('saleDownpaymentReport') ||
            Yii::app()->user->checkAccess('saleInvoiceReport') ||
            Yii::app()->user->checkAccess('salePaymentReport') ||
			Yii::app()->user->checkAccess('allFinanceReport') ||
			Yii::app()->user->checkAccess('receivableReport') ||
            Yii::app()->user->checkAccess('saleChequeReport')):
        ?>
        <fieldset id="sales-fieldset">
            <legend>Finance</legend>
            <ul id="sales-ul" style="display: table-cell">
				<?php if (Yii::app()->user->checkAccess('saleInvoiceReport')): ?>
                    <li><?php echo CHtml::link('Invoice Penjualan (Detail)', array('/report/saleInvoice/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('cashExpenseReport') || Yii::app()->user->checkAccess('cashDepositReport')): ?>
                    <li><?php echo CHtml::link('Buku Kas / Bank', array('/report/bankBook/summary')); ?><br/><br/></li>	
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleDownpaymentReport')): ?>
                    <li><?php echo CHtml::link('Uang Muka Penjualan', array('/report/saleDownpayment/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleReceiptReport')): ?>
                    <li><?php echo CHtml::link('Tanda Terima Penjualan Detail', array('/report/saleReceipt/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleChequeReport')): ?>
                    <li><?php echo CHtml::link('Penerimaan Giro Penjualan', array('/report/saleCheque/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('salePaymentReport')): ?>
                    <li><?php echo CHtml::link('Pelunasan Penjualan', array('/report/salePayment/summary')); ?><br/><br/></li>
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('receivableReport')): ?>	
                    <li><?php echo CHtml::link('Invoice Belum TT', array('/report/invoiceOutstandingReceipt/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Piutang Detail', array('/report/receivableDetail/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Piutang Customer', array('/report/customerReceivable/summary')); ?><br/><br/></li>	
                <?php endif; ?>
                <br/><br/>
				<?php if (Yii::app()->user->checkAccess('allFinanceReport')): ?>
                    <li><?php echo CHtml::link('Hutang Supplier', array('/report/supplierPayable/summary')); ?><br/><br/></li>	
                    <li><?php echo CHtml::link('Hutang Detail', array('/report/payableDetail/summary')); ?><br/><br/></li> 
                <?php endif; ?>      
				<?php if (Yii::app()->user->checkAccess('purchaseReceiptReport')): ?>
                    <li><?php echo CHtml::link('Tanda Terima Pembelian', array('/report/purchaseReceiptSummary/summary')); ?><br/><br/></li> 
                <?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('purchasePaymentReport')): ?>     
                    <li> <?php echo CHtml::link('Pembayaran Pembelian', array('/report/purchasePayment/summary')); ?><br/><br/></li>
					<li><?php echo CHtml::link('Pembayaran Pembelian Detail', array('/report/purchaseReceipt/summary')); ?><br/><br/></li>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endif; ?>

	<?php if (Yii::app()->user->checkAccess('allAccountingReport') || Yii::app()->user->checkAccess('adjustmentJournalReport')): ?>
        <fieldset id="accounting-fieldset">
            <legend>Accounting</legend>
            <ul id="accounting-ul" style="display: table-cell">
				<?php if (Yii::app()->user->checkAccess('allAccountingReport')): ?>
                    <li><?php echo CHtml::link('Faktur Pajak Masukan (Summary)', array('/report/purchaseTax/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Faktur Pajak Keluaran (Summary)', array('/report/saleTax/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Buku Besar', array('/report/generalLedger/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Balance Sheet', array('/report/balanceSheet/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Harga Pokok Penjualan', array('/report/hpp/summary')); ?><br/><br/></li>
                    <li><?php echo CHtml::link('Laba/Rugi', array('/report/profitLoss/summary')); ?><br/><br/></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('adjustmentJournalReport')): ?>
                    <li><?php echo CHtml::link('Jurnal Umum', array('/report/journalVoucher/summary')); ?><br/><br/></li>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endif; ?>
</div>