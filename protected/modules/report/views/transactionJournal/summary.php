<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/report.css');
?>
<div class="hide">
    <div class="form" style="text-align: center">
        <?php echo CHtml::beginForm(array(''), 'get'); ?>

        <div class="row" style="background-color: #DFDFDF">
            Halaman saat ini
            <?php echo CHtml::textField('page', $currentPage, array('size' => 3, 'id' => 'CurrentPage')); ?>
        </div>

<!--        <div class="row">
            Branch
            <?php /*echo CHtml::dropDownlist('BranchId', $branchId, CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- All Branch --')); ?>

            COA
            <?php echo CHtml::textField('CoaId', $coaId, array(
                'readonly' => true,
                'onclick' => 'jQuery("#coa-dialog").dialog("open"); return false;',
                'onkeypress' => 'if (event.keyCode == 13) { $("#coa-dialog").dialog("open"); return false; }'
            )); ?>
            <?php echo CHtml::openTag('span', array('id' => 'coa_name')); ?>
            <?php $coa = Account::model()->findByPk($coaId); ?>
            <?php echo CHtml::encode(CHtml::value($coa, 'name')); ?>
            <?php echo CHtml::closeTag('span');*/ ?> 
        </div>-->

        <div class="row">
            Tanggal Mulai
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'StartDate',
                'value' => $startDate,
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                    'placeholder' => 'Mulai',
                ),
            )); ?>

            Sampai
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'EndDate',
                'value' => $endDate,
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                    'placeholder' => 'Sampai',
                ),
            )); ?>
        </div>

        <div class="row">
            <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
        </div>

        <div class="row button">
            <?php echo CHtml::submitButton('Tampilkan', array('onclick' => '$("#CurrentSort").val(""); return true;')); ?>
            <?php echo CHtml::submitButton('Hapus', array('name' => 'ResetFilter'));  ?>
            <?php echo CHtml::submitButton('Simpan ke Excel', array('name' => 'SaveExcel'));  ?>
        </div>

        <?php echo CHtml::endForm(); ?>

    </div>

    <hr />

    <div class="right">
        <?php echo ReportHelper::summaryInfo($currentPage, $pageSize, $transactionJournalCount); ?>
    </div>

    <br /> 

    <?php $this->renderPartial('_summary', array(
        'transactionJournalReport' => $transactionJournalReport,
        'transactionJournalReportData' => $transactionJournalReportData,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'branchId' => $branchId,
    )); ?>
    
    <div class="clear"></div>
</div>

<div class="grid-view">
    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'coa-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'COA ',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    )); ?>
    <?php echo CHtml::beginForm(); ?>
    <div class="row">
        <div class="small-12 columns" style="padding-left: 0px; padding-right: 0px;">    
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'coa-grid',
                'dataProvider'=>$accountDataProvider,
                'filter' => $account,
                'template' => '{items}<div class="clearfix">{summary}{pager}</div>',
                'pager'=>array(
                   'cssFile'=>false,
                   'header'=>'',
                ),
                'selectionChanged'=>'js:function(id){
                    $("#CoaId").val($.fn.yiiGridView.getSelection(id));
                    $("#coa-dialog").dialog("close");
                    if ($.fn.yiiGridView.getSelection(id) == "") {
                        $("#coa_id").html("");
                        $("#coa_name").html("");
                    } else {
                        $.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url: "' . CController::createUrl('ajaxJsonCoa') . '",
                            data: $("form").serialize(),
                            success: function(data) {
                                $("#coa_id").html(data.coa_code);
                                $("#coa_name").html(data.coa_name);
                            },
                        });
                    }
                }',
                'columns'=> array(
                    'code',
                    'name',
                    array(
                        'name' => 'account_category_id',
                        'filter' => CHtml::activeDropDownList($account, 'account_category_id', CHtml::listData(AccountCategory::model()->findAll(array('order' => 'name')), 'id', 'name'), array('empty' => '-- All --')),
                        'value' => 'CHtml::value($data, "accountCategory.name")',
                    ),
                ),
            )); ?>
        </div>
    </div>
    <?php echo CHtml::endForm(); ?>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div> 

<br/>

<div class="right">
    <?php $this->widget('system.web.widgets.pagers.CLinkPager', array(
        'itemCount' => $transactionJournalCount,
        'pageSize' => $pageSize,
        'currentPage' => $currentPage - 1,
    )); ?>
</div>