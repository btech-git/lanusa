<?php
	//$supplier as Supplier model
	//$purchaseInvoice as PurchaseInvoice model
?>

<div id="supplier_div" class="row" style="background-color: #DFDFDF">
	Supplier
	<?php echo CHtml::activeDropDownlist($purchaseInvoice, 'supplier_id', CHtml::listData($supplier, 'id', 'company'), array('empty'=>'-- Supplier --')); ?>
</div>