<?php
	$this->breadcrumbs = array(
		'Sale DownPayment'=>array('admin'),
		'Create',
	);
?>

<h1>Uang Muka Penjualan</h1>

<?php echo $this->renderPartial('_form', array('saleDownpayment' => $saleDownpayment, 'customer' => $customer, 'dataProvider' => $dataProvider, 'error' => $error,)); ?>