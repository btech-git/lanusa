<?php
Yii::app()->clientScript->registerScript('userRoles', "
    function checkRoles(number, start, end) {
        if ($('#".CHtml::activeId($model, 'roles')."_' + number).prop('checked') || $('#".CHtml::activeId($model, 'roles')."_' + number).prop('disabled')) {
            for (i = start; i <= end; i++) {
                $('#".CHtml::activeId($model, 'roles')."_' + i).removeAttr('checked');
                $('#".CHtml::activeId($model, 'roles')."_' + i).attr('disabled', true);
            }
        } else {
            for (i = start; i <= end; i++) {
                $('#".CHtml::activeId($model, 'roles')."_' + i).removeAttr('disabled');
            }
        }
        console.log($('#" . CHtml::activeId($model, 'roles') . "_' + number).attr('checked'));
    }

    $(document).ready(function(){
        checkRoles(0, 1, 62);
        checkRoles(2, 6, 23);
        checkRoles(3, 24, 29);
        checkRoles(4, 30, 58);
        checkRoles(5, 59, 62);
        checkRoles();
    });

    $('#" . CHtml::activeId($model, 'roles') . "_0').click(function(){
        checkRoles(0, 1, 62);
    });

    $('#" . CHtml::activeId($model, 'roles') . "_2').click(function(){
        checkRoles(2, 6, 23);
    });

    $('#" . CHtml::activeId($model, 'roles') . "_3').click(function(){
        checkRoles(3, 24, 29);
    });

    $('#" . CHtml::activeId($model, 'roles') . "_4').click(function(){
        checkRoles(4, 30, 58);
    });
    $('#" . CHtml::activeId($model, 'roles') . "_5').click(function(){
        checkRoles(5, 59, 62);
    });
");
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        ),
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <?php if (!$model->isNewRecord): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'current_password'); ?>
        <?php echo $form->passwordField($model, 'current_password', array('size' => 32, 'maxlength' => 32)); ?>
        <?php echo $form->error($model, 'current_password'); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'new_password'); ?>
        <?php echo $form->passwordField($model, 'new_password', array('size' => 32, 'maxlength' => 32)); ?>
        <?php echo $form->error($model, 'new_password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'confirm_password'); ?>
        <?php echo $form->passwordField($model, 'confirm_password', array('size' => 32, 'maxlength' => 32)); ?>
        <?php echo $form->error($model, 'confirm_password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'address'); ?>
        <?php echo $form->textArea($model, 'address', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'note'); ?>
        <?php echo $form->textArea($model, 'note', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'note'); ?>
    </div>

    <div class="row">
        <fieldset style="width: 100%">
            <legend><span style="font-weight: bold">Roles</span></legend>
            <?php $this->renderPartial('_role', array('model' => $model, 'counter' => 0)); ?>
        </fieldset>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'is_inactive'); ?>
        <?php echo $form->dropDownList($model, 'is_inactive', array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
        <?php echo $form->error($model, 'is_inactive'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->