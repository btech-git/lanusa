<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
	$("#PageSize").val("' . $generalLedgerSummary->dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($generalLedgerSummary->dataProvider->pagination->getCurrentPage(false) + 1) . '");
	$("#CurrentSort").val("' . $currentSort . '");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/report.css');
?>

<div class="hide">
    <div class="form" style="text-align: center">
        <?php echo CHtml::beginForm(array(''), 'get'); ?>

<!--        <div class="row" style="background-color: #DFDFDF">
            Cabang
            <?php /*echo CHtml::hiddenField('BranchId'); ?>
            <?php echo CHtml::activeDropDownlist($branch, 'id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array(
                'empty' => '-- Pilih Cabang --',
                'onchange' => '
                    $("#BranchId").val(this.value);
                    $.ajax({
                        type: "POST",
                        url: "' . CController::createUrl('ajaxHtmlAccount') . '",
                        data: $("#BranchId").serialize(),
                        success: function(html) {
                            $("#account_div").html(html);
                        }
                    });
                '
            ));*/ ?>
        </div>   -->

<!--        <div class="row" style="background-color: #DFDFDF">
            Cabang
            <?php //echo CHtml::dropDownlist('BranchId', $branchId, CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty'=>'-- Semua Cabang --'));  ?>
        </div>-->

        <div class="row">
            Jumlah per Halaman
            <?php echo CHtml::textField('PageSize', '', array('size' => 3)); ?>

            Halaman saat ini
            <?php echo CHtml::textField('page', '', array('size' => 3, 'id' => 'CurrentPage')); ?>
        </div>

        <div class="row">
            Tanggal Mulai
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'StartDate',
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                ),
            )); ?>

            Sampai
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'EndDate',
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                ),
            )); ?>
        </div>

        <?php /*$this->renderPartial('_account', array(
            'accounts' => $accounts,
            'startAccount' => $startAccount,
            'endAccount' => $endAccount,
        ));*/ ?>

        <div class="row" style="background-color: #DFDFDF">
            Akun
            <?php echo CHtml::textField('AccountIds', $accountIds, array(
                'readonly' => true,
                'onclick' => 'jQuery("#coa-dialog").dialog("open"); return false;',
                'onkeypress' => 'if (event.keyCode == 13) { $("#coa-dialog").dialog("open"); return false; }'
            )); ?>
            <?php echo CHtml::openTag('span', array('id' => 'coa_name')); ?>
            <?php $coa = Account::model()->findByPk($account->id); ?>
            <?php echo CHtml::encode(CHtml::value($coa, 'name')); ?>
            <?php echo CHtml::closeTag('span'); ?> 
        </div>

        <div class="row">
            <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
        </div>

        <div class="row button">
            <?php echo CHtml::submitButton('Show', array('onclick' => '$("#CurrentSort").val(""); return true;')); ?>
            <?php echo CHtml::resetButton('Clear'); ?>
        </div>

        <div class="row button">
            <?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
        </div>

        <?php echo CHtml::endForm(); ?>

    </div>

    <hr />

    <div class="right"><?php echo ReportHelper::summaryText($generalLedgerSummary->dataProvider); ?></div>
    <div class="clear"></div>
    <div class="right">
        <?php //echo ReportHelper::sortText($generalLedgerSummary->details->sort, array('Tanggal'));  ?>
        <?php //echo ReportHelper::sortText($generalLedgerSummary->dataProvider->sort, array('Code'));  ?>
    </div>
    <div class="clear"></div>
</div>

<div>
    <?php $this->renderPartial('_summary', array(
        'account' => $account,
        'generalLedgerSummary' => $generalLedgerSummary,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'ledgerBeginningBalanceData' => $ledgerBeginningBalanceData,
        'generalLedgerReportData' => $generalLedgerReportData,
    )); ?>
</div>

<div class="hide">
    <div class="right">
        <?php
        $this->widget('system.web.widgets.pagers.CLinkPager', array(
            'itemCount' => $generalLedgerSummary->dataProvider->pagination->itemCount,
            'pageSize' => $generalLedgerSummary->dataProvider->pagination->pageSize,
            'currentPage' => $generalLedgerSummary->dataProvider->pagination->getCurrentPage(false),
        ));
        ?>
    </div>
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
                    $("#' . CHtml::activeId($account, 'id') . '").val($.fn.yiiGridView.getSelection(id));
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
                    array(
                        'id' => 'CoaIds',
                        'class' => 'CCheckBoxColumn',
                        'selectableRows' => '1000',
                    ),
                    'code',
                    'name',
                    array(
                        'name' => 'account_category_id',
                        'filter' => CHtml::activeDropDownList($account, 'account_category_id', CHtml::listData(AccountCategory::model()->findAll(array('order' => 'name')), 'id', 'name'), array('empty' => '-- All --')),
                        'value' => 'CHtml::value($data, "accountCategory.name")',
                    ),
                    array(
                        'name' => 't.branch_id',
                        'filter' => CHtml::activeDropDownList($account, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 'name')), 'id', 'code'), array('empty' => '-- All --')),
                        'value' => 'CHtml::value($data, "branch.code")',
                    ),
                ),
            )); ?>
            <?php echo CHtml::htmlButton('Add COA', array(
                'onclick' => '
                    $("#coa-dialog").dialog("close");
                    var coaIds = [];
                    $("input[name^=CoaIds]:checked").each(function() {
                        coaIds.push($(this).val());
                    });
                    $("#AccountIds").val(coaIds.join(","));
                ',
            )); ?>

        </div>
    </div>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>