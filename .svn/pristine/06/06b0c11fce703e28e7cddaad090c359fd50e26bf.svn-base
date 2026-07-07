<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'account_category_id'); ?>
		<?php echo $form->dropDownList($model, 'account_category_id', CHtml::listData(accountCategory::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model, 'account_category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'code'); ?>
		<?php echo $form->textField($model, 'code', array('size'=>60, 'maxlength'=>60)); ?>
		<?php echo $form->error($model, 'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('size'=>60, 'maxlength'=>60)); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'branch_id'); ?>
		<?php echo $form->dropDownlist($model, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model, 'branch_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'is_inactive'); ?>
		<?php echo $form->dropDownList($model,'is_inactive', array(ActiveRecord::ACTIVE => ActiveRecord::ACTIVE_LITERAL, ActiveRecord::INACTIVE => ActiveRecord::INACTIVE_LITERAL)); ?>
		<?php echo $form->error($model, 'is_inactive'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
