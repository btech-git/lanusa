<?php
	$this->breadcrumbs = array(
		'Purchase Payment'=>array('admin'),
		'Create',
	);
?>

<h1>Revisi Pembayaran Pembelian Barang</h1>

<?php echo $this->renderPartial('_form', array(
	'purchasePayment' => $purchasePayment, 
	'purchaseReceiptHeader' => $purchaseReceiptHeader, 
	'account' => $account, 
	'cnMonth' => strtoupper($cnMonth),
	'purchaseReceiptDataProvider' => $purchaseReceiptDataProvider, 
	'accountDataProvider' => $accountDataProvider,
	'supplierCompany' => $supplierCompany
)); ?>