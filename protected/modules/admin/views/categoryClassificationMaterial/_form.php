<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'category-classification-material-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'category_classification_id'); ?>
		<?php echo CHtml::activeDropDownList($model, 'category_classification_id', CHtml::listData(CategoryClassification::model()->findAll(array(
				'with' => array(
					'category',
					'classification'),
				'order' => 'category.name, classification.name'
			)), 'id', 'name')); ?>
		<?php echo $form->error($model, 'category_classification_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'material_id'); ?>
		<?php echo $form->dropDownlist($model, 'material_id', CHtml::listData(Material::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model, 'material_id'); ?>
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