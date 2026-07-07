<?php
$this->breadcrumbs = array(
	'Fee Invoices'=>array('index'),
	'Create',
);

$this->menu = array(
	array('label'=>'List FeeInvoice', 'url'=>array('index')),
	array('label'=>'Manage FeeInvoice', 'url'=>array('admin')),
);
?>

<h1>Create Invoice</h1>

<?php echo $this->renderPartial('_form', array(
    'model'=>$model,
    'customer' => $customer,
    'dataProvider' => $dataProvider,
)); ?>