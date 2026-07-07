<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

        $("#EndDate").val("'.$endDate.'");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/report.css');
?>

<div class="hide">
    <div class="form" style="text-align: center">
        <?php echo CHtml::beginForm(array(''), 'get'); ?>
            <div class="row" style="background-color: #DFDFDF">
                Cabang
                <?php echo CHtml::dropDownlist('BranchId', $branchId,
                    CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array(
                        'empty'=>'-- Pilih Cabang --'
                    )
                ); ?>
            </div>
            
            <div class="row">
                Akhir Periode
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'EndDate',
                    'options'=>array(
                        'dateFormat'=>'yy-mm-dd',
                    'changeMonth'=>true,
                    'changeYear'=>true,
                    ),
                    'htmlOptions'=>array(
                        'readonly'=>true,
                    ),
                )); ?>
            </div>
			
            <div class="row button">
                <?php echo CHtml::submitButton('Show', array('onclick'=>'$("#CurrentSort").val(""); return true;')); ?>
                <?php echo CHtml::resetButton('Clear'); ?>
            </div>

            <div class="row button">
                <?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
            </div>
                
        <?php echo CHtml::endForm(); ?>
    </div>
    <hr />
</div>

<div>
    <?php $this->renderPartial('_summary', array(
        'accounts' => $accounts,
        'branchId' => $branchId,
        'endDate' => $endDate
    )); ?>
</div>
