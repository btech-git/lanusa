<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'cn_ordinal'); ?>
		<?php echo $form->textField($model, 'cn_ordinal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'cn_month'); ?>
		<?php echo $form->textField($model, 'cn_month'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'cn_year'); ?>
		<?php echo $form->textField($model, 'cn_year'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'date'); ?>
		<?php echo $form->textField($model, 'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'fee_amount'); ?>
		<?php echo $form->textField($model, 'fee_amount', array('size'=>18, 'maxlength'=>18)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'note'); ?>
		<?php echo $form->textArea($model, 'note', array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'customer_id'); ?>
		<?php echo $form->textField($model, 'customer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'account_id'); ?>
		<?php echo $form->textField($model, 'account_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'tax_item_value'); ?>
		<?php echo $form->textField($model, 'tax_item_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'tax_item_amount'); ?>
		<?php echo $form->textField($model, 'tax_item_amount', array('size'=>18, 'maxlength'=>18)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'tax_service_value'); ?>
		<?php echo $form->textField($model, 'tax_service_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'tax_service_amount'); ?>
		<?php echo $form->textField($model, 'tax_service_amount', array('size'=>18, 'maxlength'=>18)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'is_inactive'); ?>
		<?php echo $form->textField($model, 'is_inactive'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->