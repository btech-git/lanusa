<?php
	$this->breadcrumbs = array(
		'Sale Cheque'=>array('admin'),
		'Create',
	);
?>

<h1>Revisi Penerimaan Giro Penjualan</h1>

<?php echo $this->renderPartial('_form', array( 
	'saleCheque'=>$saleCheque, 
	'saleReceiptHeader'=>$saleReceiptHeader, 
//	'cnMonth' => strtoupper($cnMonth), 
	'saleReceiptHeaderDataProvider' => $saleReceiptHeaderDataProvider, 
	'error'=>$error,
	'customerDataProvider' => $customerDataProvider,
	'customer' => $customer,
)); 
?>
