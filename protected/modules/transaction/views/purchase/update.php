<?php
$this->breadcrumbs = array(
	'Purchase'=>array('admin'),
	'Update',
);
?>

<h1>Revisi Pemesanan Pembelian Barang</h1>

<?php echo $this->renderPartial('_form', array('purchase'=>$purchase, 'product'=>$product, 'dataProvider' => $dataProvider)); ?>