<?php
	$this->breadcrumbs = array(
		'Purchase Invoice'=>array('admin'),
		'Create',
	);
?>

<h1>Penerimaan Faktur Pembelian</h1>

<?php echo $this->renderPartial('_form', array(
	'purchaseInvoice' => $purchaseInvoice, 
	'purchaseHeader' => $purchaseHeader, 
	'supplier' => $supplier,
	'cnMonth' => strtoupper($cnMonth), 
	'dataProvider' => $dataProvider, 
	'supplierDataProvider' => $supplierDataProvider,
)); ?>