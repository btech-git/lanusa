<?php
$this->breadcrumbs = array(
    'Purchase Receipt' => array('admin'),
    'Create',
);
?>

<h1>Revisi Tanda Terima Pembelian</h1>

<?php echo $this->renderPartial('_form', array(
	'purchaseReceipt' => $purchaseReceipt, 
	'receive' => $receive,
	'supplier' => $supplier, 
	'cnMonth' => strtoupper($cnMonth), 
	'receiveDataProvider' => $receiveDataProvider, 
	'supplierDataProvider' => $supplierDataProvider,
	'supplierCompany' => $supplierCompany
	)); ?>