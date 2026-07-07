<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'category-brand-handling-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'category_brand_id'); ?>
		<?php echo CHtml::activeDropDownList($model, 'category_brand_id', CHtml::listData(CategoryBrand::model()->findAll(array(
			'with' => array(
					'category',
					'brand'
				),
				'order' => 'category.name, brand.name'
		)), 'id', 'name')); ?>
		<?php echo $form->error($model, 'category_brand_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'handling_id'); ?>
		<?php echo $form->dropDownlist($model, 'handling_id', CHtml::listData(Handling::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model, 'handling_id'); ?>
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