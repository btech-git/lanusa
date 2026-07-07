<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>$action,
	'method'=>'get',
        'htmlOptions'=>array(
                'onsubmit'=>"$.fn.yiiGridView.update('product-grid', { data: $(this).serialize() }); return false;",
        ),
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Kategori'); ?>
		<?php echo $form->dropDownList($model,'category_id', CHtml::listData(Category::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty'=>'-- Select Category --',
                        'onchange'=>CHtml::ajax(array(
                                'type'=>'POST',
                                'url'=>CController::createUrl('searchSpecificationAjax', array('view'=>'//site/_search', 'action'=>$action)),
                                'success'=>'function(html) {
                                        $("#search_div").html(html);
                                        '.CHtml::ajax(array(
                                                'type'=>'POST',
                                                'dataType'=>'JSON',
                                                'data'=>'js:$("form").serialize()',
                                                'url'=>CController::createUrl('categorySelectionAjaxData'),
                                                'success'=>'function(data) {
                                                        $("#'.CHtml::activeId($model, 'brand_id').'").html(data.brandOptions);
                                                        $("#'.CHtml::activeId($model, 'classification_id').'").html(data.classificationOptions);
                                                        $("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
                                                        $("#'.CHtml::activeId($model, 'grade_id').'").html(data.gradeOptions);
                                                        $("#'.CHtml::activeId($model, 'material_id').'").html(data.materialOptions);
                                                        $("#'.CHtml::activeId($model, 'thickness_id').'").html(data.thicknessOptions);
                                                        $("#'.CHtml::activeId($model, 'type_id').'").html(data.typeOptions);
                                                        $("#'.CHtml::activeId($model, 'variety_id').'").html(data.varietyOptions);
                                                }',
                                        )).'
                                }',
                        )),
                )); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Nama'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Satuan'); ?>
		<?php echo $form->dropDownList($model,'unit_id', CHtml::listData(Unit::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty'=>'')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'size'); ?>
		<?php echo $form->textField($model,'size',array('size'=>60,'maxlength'=>60)); ?>
	</div>

        <?php if (in_array('length', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'length'); ?>
                        <?php echo $form->textField($model,'length',array('size'=>60,'maxlength'=>60)); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('classification_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Class'); ?>
                        <?php echo $form->dropDownList($model,'classification_id', $listData['classification'], array('empty'=>'',
                                'onchange'=>CHtml::ajax(array(
                                        'type'=>'POST',
                                        'dataType'=>'JSON',
                                        'url'=>CController::createUrl('classificationSelectionAjaxData'),
                                        'success'=>'function(data) {
                                                $("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
                                                $("#'.CHtml::activeId($model, 'material_id').'").html(data.materialOptions);
                                                $("#'.CHtml::activeId($model, 'variety_id').'").html(data.varietyOptions);
                                        }',
                                )),
                        )); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('material_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Material'); ?>
                        <?php echo $form->dropDownList($model,'material_id', $listData['material'], array('empty'=>'',
                                'onchange'=>CHtml::ajax(array(
                                        'type'=>'POST',
                                        'dataType'=>'JSON',
                                        'url'=>CController::createUrl('materialSelectionAjaxData'),
                                        'success'=>'function(data) {
                                                $("#'.CHtml::activeId($model, 'grade_id').'").html(data.gradeOptions);
                                        }',
                                )),
                        )); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('grade_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Grade'); ?>
                        <?php echo $form->dropDownList($model,'grade_id', $listData['grade'], array('empty'=>'',
                                'onchange'=>CHtml::ajax(array(
                                        'type'=>'POST',
                                        'dataType'=>'JSON',
                                        'url'=>CController::createUrl('gradeSelectionAjaxData'),
                                        'success'=>'function(data) {
                                                $("#'.CHtml::activeId($model, 'brand_id').'").html(data.brandOptions);
                                                $("#'.CHtml::activeId($model, 'thickness_id').'").html(data.thicknessOptions);
                                        }',
                                )),
                        )); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('brand_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Merk'); ?>
                        <?php echo $form->dropDownList($model,'brand_id', $listData['brand'], array('empty'=>'',
                                'onchange'=>CHtml::ajax(array(
                                        'type'=>'POST',
                                        'dataType'=>'JSON',
                                        'url'=>CController::createUrl('brandSelectionAjaxData'),
                                        'success'=>'function(data) {
                                                $("#'.CHtml::activeId($model, 'body_type_id').'").html(data.bodyOptions);
                                                $("#'.CHtml::activeId($model, 'connection_id').'").html(data.connectionOptions);
                                                $("#'.CHtml::activeId($model, 'disc_material_id').'").html(data.discOptions);
                                                $("#'.CHtml::activeId($model, 'handling_id').'").html(data.handlingOptions);
                                                $("#'.CHtml::activeId($model, 'type_id').'").html(data.typeOptions);
                                        }',
                                )),
                        )); ?>
                </div>
        <?php endif; ?>
        
        <?php if (in_array('thickness_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Ketebalan'); ?>
                        <?php echo $form->dropDownList($model,'thickness_id', $listData['thickness'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>
        
        <?php if (in_array('body_material_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Body'); ?>
                        <?php echo $form->dropDownList($model,'body_type_id', $listData['body'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('connection_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Connection'); ?>
                        <?php echo $form->dropDownList($model,'connection_id', $listData['connection'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('disc_material_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Disc Material'); ?>
                        <?php echo $form->dropDownList($model,'disc_material_id', $listData['disc'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('handling_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Handling'); ?>
                        <?php echo $form->dropDownList($model,'handling_id', $listData['handling'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('type_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Tipe'); ?>
                        <?php echo $form->dropDownList($model,'type_id', $listData['type'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('variety_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Jenis'); ?>
                        <?php echo $form->dropDownList($model,'variety_id', $listData['variety'], array('empty'=>'')); ?>
                </div>
        <?php endif; ?>
        
        <?php if (in_array('drat', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Drat / ND'); ?>
                        <?php echo $form->dropDownList($model,'drat', array('1'=>'Drat', '2'=>'Non-Drat'), array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('physical_thickness', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->labelEx($model,'Tebal Fisik'); ?>
                        <?php echo $form->textField($model,'physical_thickness'); ?>
                        <?php echo $form->error($model,'physical_thickness'); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('connection_material_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Connection Material'); ?>
                        <?php echo $form->dropDownList($model,'connection_material_id', CHtml::listData(ConnectionMaterial::model()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
                </div>
        <?php endif; ?>
        
        <?php if (in_array('parameter_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Parameter'); ?>
                        <?php echo $form->dropDownList($model,'parameter_id', CHtml::listData(Parameter::model()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('range_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Range'); ?>
                        <?php echo $form->dropDownList($model,'range_id', CHtml::listData(Range::model()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('bellow_id', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Bellow'); ?>
                        <?php echo $form->dropDownList($model,'bellow_id', CHtml::listData(Bellow::model()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
                </div>
        <?php endif; ?>

        <?php if (in_array('connection_diameter', $specificationList)): ?>
                <div class="row">
                        <?php echo $form->label($model,'Connection Diameter'); ?>
                        <?php echo $form->textField($model,'connection_diameter',array('size'=>60,'maxlength'=>60)); ?>
                </div>
        <?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->