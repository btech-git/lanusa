<?php
	$this->breadcrumbs = array(
		'Sale Payment'=>array('admin'),
		'Create',
	);
?>

<h1>Pembayaran Penjualan Barang</h1>

<?php echo $this->renderPartial('_form', array('salePayment' => $salePayment, 'saleReceiptHeader' => $saleReceiptHeader, 'account' => $account, 'cnMonth' => strtoupper($cnMonth), 'saleReceiptDataProvider' => $saleReceiptDataProvider, 'accountDataProvider' => $accountDataProvider)); ?>