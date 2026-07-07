<?php
$this->breadcrumbs = array(
	'Fee Invoices'=>array('index'),
	$model->id=>array('view', 'id'=>$model->id),
	'Update',
);

$this->menu = array(
	array('label'=>'List FeeInvoice', 'url'=>array('index')),
	array('label'=>'Create FeeInvoice', 'url'=>array('create')),
	array('label'=>'View FeeInvoice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FeeInvoice', 'url'=>array('admin')),
);
?>

<h1>Update Invoice <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array(
    'model'=>$model,
    'customer' => $customer,
    'dataProvider' => $dataProvider,
)); ?>