<?php echo CHtml::activeLabel($model, 'account_id'); ?>
<?php echo CHtml::activeDropDownlist($model, 'account_id',
	$accounts,
	array(
		'empty' => '-Account-'
	)); ?>
<?php echo CHtml::error($model, 'account_id'); ?>