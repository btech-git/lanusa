<?php
	//$supplier as Supplier model
?>


<div id="account_div" class="row" style="background-color: #DFDFDF">
	Account
	<?php echo CHtml::dropDownlist('AccountId', $accountId,
		CHtml::listData($accounts, 'id', 'name'), 
		array(
			'empty'=>'-- Account --'
		)); ?>
	
</div>