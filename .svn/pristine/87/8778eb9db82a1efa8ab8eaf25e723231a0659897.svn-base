<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
        'action'=>$action,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->label($model,'Kategori'); ?>
        <?php if ($model->isNewRecord): ?>
                <?php echo $form->dropDownList($model,'category_id', CHtml::listData(Category::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty'=>'-- Select Category --',
                    'onchange'=>CHtml::ajax(array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('selectSpecificationAjax', array('view'=>'_form', 'action'=>$action)),
                        'success'=>'function(html) {
                            $("#form_div").html(html);
                            '.CHtml::ajax(array(
                                'type'=>'POST',
                                'dataType'=>'JSON',
                                'data'=>'js:$("form").serialize()',
                                'url'=>CController::createUrl('categorySelectionAjaxData', array('emptyText'=>'Not Available')),
                                'success'=>'function(data) {
                                    $("#'.CHtml::activeId($model, 'brand_id').'").html(data.brandOptions);
                                    $("#'.CHtml::activeId($model, 'classification_id').'").html(data.classificationOptions);
                                    $("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
                                    $("#'.CHtml::activeId($model, 'material_id').'").html(data.materialOptions);
                                    $("#'.CHtml::activeId($model, 'thickness_id').'").html(data.thicknessOptions);
                                    $("#'.CHtml::activeId($model, 'type_id').'").html(data.typeOptions);
                                    $("#'.CHtml::activeId($model, 'variety_id').'").html(data.varietyOptions);
                                }',
                            )).'
                        }',
                    )),
                )); ?>
                <?php echo $form->error($model,'category_id'); ?>
        <?php else: ?>
            <?php echo $form->hiddenField($model,'category_id'); ?>
            <?php echo CHtml::encode(CHtml::value($model,'category.name')); ?>
        <?php endif; ?>
    </div>

	<div class="row">
		<?php echo $form->label($model, 'Kode'); ?>
		<?php echo $form->textField($model, 'code',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model, 'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'Nama'); ?>
		<?php echo $form->textField($model, 'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Satuan'); ?>
			<?php echo $form->dropDownList($model,'unit_id', CHtml::listData(Unit::model()->findAll(array('order' => 't.name')), 'id', 'name')); ?>
		<?php echo $form->error($model,'unit_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Harga Beli'); ?>
		<?php echo $form->textField($model,'buying_price',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'buying_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Harga Jual'); ?>
		<?php echo $form->textField($model,'selling_price',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'selling_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'size'); ?>
		<?php echo $form->textField($model,'size',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'size'); ?>
	</div>
        <?php if (in_array('length', $specificationList)): ?>
			<div class="row">
				<?php echo $form->labelEx($model,'length'); ?>
				<?php echo $form->textField($model,'length',array('size'=>60,'maxlength'=>60)); ?>
				<?php echo $form->error($model,'length'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('classification_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Class Tebal'); ?>
				<?php echo $form->dropDownList($model,'classification_id', $listData['classification'], array('empty'=>'Not Available',
					'onchange'=>CHtml::ajax(array(
						'type'=>'POST',
						'dataType'=>'JSON',
						'url'=>CController::createUrl('classificationSelectionAjaxData', array('emptyText'=>'Not Available')),
						'success'=>'function(data) {
								$("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
								$("#'.CHtml::activeId($model, 'material_id').'").html(data.materialOptions);
								$("#'.CHtml::activeId($model, 'variety_id').'").html(data.varietyOptions);
						}',
					)),
				)); ?>
				<?php echo $form->error($model,'classification_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('material_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Material'); ?>
				<?php echo $form->dropDownList($model,'material_id', $listData['material'], array('empty'=>'Not Available',
					'onchange'=>CHtml::ajax(array(
						'type'=>'POST',
						'dataType'=>'JSON',
						'url'=>CController::createUrl('materialSelectionAjaxData', array('emptyText'=>'Not Available')),
						'success'=>'function(data) {
							$("#'.CHtml::activeId($model, 'grade_id').'").html(data.gradeOptions);
						}',
					)),
				)); ?>
				<?php echo $form->error($model,'material_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('grade_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Grade'); ?>
				<?php echo $form->dropDownList($model,'grade_id', $listData['grade'], array('empty'=>'Not Available',
					'onchange'=>CHtml::ajax(array(
						'type'=>'POST',
						'dataType'=>'JSON',
						'url'=>CController::createUrl('gradeSelectionAjaxData', array('emptyText'=>'Not Available')),
						'success'=>'function(data) {
							$("#'.CHtml::activeId($model, 'brand_id').'").html(data.brandOptions);
							$("#'.CHtml::activeId($model, 'thickness_id').'").html(data.thicknessOptions);
						}',
					)),
				)); ?>
				<?php echo $form->error($model,'grade_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('brand_id', $specificationList)): ?>
                <div class="row">
					<?php echo $form->label($model,'Merk'); ?>
					<?php echo $form->dropDownList($model,'brand_id', $listData['brand'], array('empty'=>'Not Available',
						'onchange'=>CHtml::ajax(array(
							'type'=>'POST',
							'dataType'=>'JSON',
							'url'=>CController::createUrl('brandSelectionAjaxData', array('emptyText'=>'Not Available')),
							'success'=>'function(data) {
								$("#'.CHtml::activeId($model, 'body_type_id').'").html(data.bodyOptions);
								$("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
								$("#'.CHtml::activeId($model, 'disc_material_id').'").html(data.discOptions);
								$("#'.CHtml::activeId($model, 'handling_id').'").html(data.handlingOptions);
								$("#'.CHtml::activeId($model, 'type_id').'").html(data.typeOptions);
							}',
						)),
					)); ?>
					<?php echo $form->error($model,'brand_id'); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('thickness_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Ketebalan'); ?>
				<?php echo $form->dropDownList($model,'thickness_id', $listData['thickness'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'thickness_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('body_type_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Body'); ?>
				<?php echo $form->dropDownList($model,'body_type_id', $listData['body'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'body_type_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('connection_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Connection'); ?>
				<?php echo $form->dropDownList($model,'connection_id', $listData['connection'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'connection_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('disc_material_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Disc'); ?>
				<?php echo $form->dropDownList($model,'disc_material_id', $listData['disc'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'disc_material_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('handling_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Handling'); ?>
				<?php echo $form->dropDownList($model,'handling_id', $listData['handling'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'handling_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('type_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Tipe'); ?>
				<?php echo $form->dropDownList($model,'type_id', $listData['type'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'type_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('variety_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Jenis'); ?>
				<?php echo $form->dropDownList($model,'variety_id', $listData['variety'], array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'variety_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('drat', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Drat / ND'); ?>
				<?php echo $form->dropDownList($model,'drat', array('1'=>'Drat', '2'=>'Non-Drat'), array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'drat'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('physical_thickness', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Tebal Fisik'); ?>
				<?php echo $form->textField($model,'physical_thickness'); ?>
				<?php echo $form->error($model,'physical_thickness'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('connection_material_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Connection'); ?>
				<?php echo $form->dropDownList($model,'connection_material_id', CHtml::listData(ConnectionMaterial::model()->findAll(), 'id', 'name'), array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'connection_material_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('parameter_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Parameter'); ?>
				<?php echo $form->dropDownList($model,'parameter_id', CHtml::listData(Parameter::model()->findAll(), 'id', 'name'), array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'parameter_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('range_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Range'); ?>
				<?php echo $form->dropDownList($model,'range_id', CHtml::listData(Range::model()->findAll(), 'id', 'name'), array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'range_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('bellow_id', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Bellow'); ?>
				<?php echo $form->dropDownList($model,'bellow_id', CHtml::listData(Bellow::model()->findAll(), 'id', 'name'), array('empty'=>'Not Available')); ?>
				<?php echo $form->error($model,'bellow_id'); ?>
			</div>
        <?php endif; ?>

        <?php if (in_array('connection_diameter', $specificationList)): ?>
			<div class="row">
				<?php echo $form->label($model,'Connection Diameter'); ?>
				<?php echo $form->textField($model,'connection_diameter'); ?>
				<?php echo $form->error($model,'connection_diameter'); ?>
			</div>
        <?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'is_inactive'); ?>
		<?php echo $form->dropDownList($model,'is_inactive', array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
		<?php echo $form->error($model,'is_inactive'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->