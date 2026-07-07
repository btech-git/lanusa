<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'category-material-grade-thickness-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'category_material_grade_id'); ?>
		<?php echo CHtml::activeDropDownList($model, 'category_material_grade_id', CHtml::listData(CategoryMaterialGrade::model()->findAll(array(
			'with' => array(
				'categoryMaterial' => array('with' => array(
					'category',
					'material'
				)),
				'grade'
			),
			'order' => 'category.name, material.name, grade.name'
		)), 'id', 'name')); ?>
		<?php echo $form->error($model, 'category_material_grade_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'thickness_id'); ?>
		<?php echo $form->dropDownlist($model, 'thickness_id', CHtml::listData(Thickness::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model, 'thickness_id'); ?>
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