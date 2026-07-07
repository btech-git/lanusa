
<?php
$this->pageTitle=Yii::app()->name . ' - Lanusa';
$this->breadcrumbs=array(
	'Lanusa',
);
Yii::app()->clientScript->registerScript('transaction', "
	if (!document.getElementById('purchase-ul').getElementsByTagName('li').length)
		document.getElementById('purchase-fieldset').style.display = 'none';
	if (!document.getElementById('sales-ul').getElementsByTagName('li').length)
		document.getElementById('sales-fieldset').style.display = 'none';
	if (!document.getElementById('accounting-ul').getElementsByTagName('li').length)
		document.getElementById('accounting-fieldset').style.display = 'none';
");
?>
<h1>Revisi Transaksi</h1>

<?php //$this->renderPartial('/site/_mode', array('view'=>'edit')); ?>

<div class="form"> 
	<?php if (Yii::app()->user->checkAccess('purchaseEdit') 
		|| Yii::app()->user->checkAccess('saleEdit') 
		|| Yii::app()->user->checkAccess('purchaseReturnEdit') 
		|| Yii::app()->user->checkAccess('saleReturnEdit')
		|| Yii::app()->user->checkAccess('deliveryEdit')
		|| Yii::app()->user->checkAccess('receiveEdit')
	): ?>
		<fieldset id="purchase-fieldset">
			<legend>Marketing</legend>
			<ul id="purchase-ul" style="display: table-cell">
				<?php if (Yii::app()->user->checkAccess('saleEdit')): ?>
					<li><?php echo CHtml::link('Penjualan Barang', array('/transaction/sale/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('deliveryEdit')): ?>
					<li><?php echo CHtml::link('Pengiriman Barang', array('/transaction/delivery/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleReturnEdit')): ?>
					<li><?php echo CHtml::link('Retur Penjualan', array('/transaction/saleReturn/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('purchaseEdit')): ?>
					<li><?php echo CHtml::link('Pembelian Barang', array('/transaction/purchase/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('receiveEdit')): ?>
					<li><?php echo CHtml::link('Penerimaan Barang', array('/transaction/receive/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('purchaseReturnEdit')): ?>
					<li><?php echo CHtml::link('Retur Pembelian', array('/transaction/purchaseReturn/admin')); ?><br/><br/></li>
				<?php endif; ?>
			</ul>
		</fieldset>
	<?php endif; ?>
	
	<?php if (Yii::app()->user->checkAccess('saleChequeEdit') 
		|| Yii::app()->user->checkAccess('purchaseReceiptEdit') 
		|| Yii::app()->user->checkAccess('salePaymentEdit') 
		|| Yii::app()->user->checkAccess('purchasePaymentEdit') 
		|| Yii::app()->user->checkAccess('salesDownpaymentEdit') 
		|| Yii::app()->user->checkAccess('saleInvoiceEdit') 
		|| Yii::app()->user->checkAccess('saleReceiptEdit')
		|| Yii::app()->user->checkAccess('cashExpenseEdit')
		|| Yii::app()->user->checkAccess('cashDepositEdit')
		|| Yii::app()->user->checkAccess('adjustmentJournalEdit')
	): ?>
		<fieldset id="sales-fieldset">
			<legend>Finance</legend>
			<ul id="sales-ul" style="display: table-cell">
				<?php if (Yii::app()->user->checkAccess('saleInvoiceEdit')): ?>
					<li><?php echo CHtml::link('Invoice Penjualan', array('/transaction/saleInvoice/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('cashExpenseEdit')): ?>
					<li><?php echo CHtml::link('Pengeluaran Kas / Bank', array('/transaction/expense/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('cashDepositEdit')): ?>
					<li><?php echo CHtml::link('Penerimaan Kas / Bank', array('/transaction/deposit/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleDownpaymentEdit')): ?>
					<li><?php echo CHtml::link('Uang Muka Penjualan', array('/transaction/saleDownpayment/admin')); ?><br/><br/></li>
				<?php endif; ?>  
				<?php if (Yii::app()->user->checkAccess('saleReceiptEdit')): ?>
					<li><?php echo CHtml::link('Tanda Terima Penjualan', array('/transaction/saleReceipt/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('saleChequeEdit')): ?>
					<li><?php echo CHtml::link('Giro Penjualan', array('/transaction/saleCheque/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('salePaymentEdit')): ?>
					<li><?php echo CHtml::link('Pelunasan Penjualan', array('/transaction/salePayment/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('purchaseReceiptEdit')): ?>         
					<li><?php echo CHtml::link('Tanda Terima Pembelian', array('/transaction/purchaseReceipt/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('purchasePaymentEdit')): ?>
					<li><?php echo CHtml::link('Pembayaran Pembelian', array('/transaction/purchasePayment/admin')); ?><br/><br/></li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('adjustmentJournalEdit')): ?>
					<li><?php echo CHtml::link('Jurnal Umum', array('/transaction/journalVoucher/admin')); ?><br/><br/></li>
				<?php endif; ?>
			</ul>
		</fieldset>
	<?php endif; ?>
</div>

