<?php
	$this->breadcrumbs = array(
		'Sale Return'=>array('admin'),
		'Create',
	);
?>

<h1>Retur Penjualan Barang</h1>

<?php echo $this->renderPartial('_form', array( 
	'saleReturn' => $saleReturn, 
	'saleInvoice' => $saleInvoice,
	'product' => $product,
	'cnMonth' => $cnMonth, 
	'saleInvoiceDataProvider' => $saleInvoiceDataProvider, 
	'productDataProvider' => $productDataProvider, 
	'cnOrdinalSale' => $cnOrdinalSale,
	'cnMonthSale' => $cnMonthSale,
	'cnYearSale' => $cnYearSale,
	'customerName' => $customerName
)); ?>
