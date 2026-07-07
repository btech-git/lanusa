

<div id="account_div" class="row" style="background-color: #DFDFDF">
	Account
	<?php echo CHtml::dropDownlist('StartAccount', $startAccount,
		CHtml::listData($accounts, 'code', 'codeAndName'), 
		array(
			'empty'=>'-- Account --'
		)); ?>
	<?php echo CHtml::dropDownlist('EndAccount', $endAccount,
		CHtml::listData($accounts, 'code', 'codeAndName'), 
		array(
			'empty'=>'-- Account --'
		)); ?>
	
</div>