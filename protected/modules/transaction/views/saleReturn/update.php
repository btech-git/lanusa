<?php
	$this->breadcrumbs = array(
		'Sale Return'=>array('admin'),
		'Update',
	);
?>

<h1>Revisi Retur Penjualan Barang</h1>


<?php echo $this->renderPartial('_form', array( 
	'saleReturn' => $saleReturn,
	'saleInvoice' => $saleInvoice,
	'product' => $product,
	'cnMonth' => strtoupper($cnMonth),
	'saleInvoiceDataProvider' => $saleInvoiceDataProvider, 
	'productDataProvider' => $productDataProvider,
	'customerName' => $customerName	
)); ?>
