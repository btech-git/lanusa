

<div id="account_div" class="row" style="background-color: #DFDFDF">
    Account
    <?php echo CHtml::textField('AccountIds', $accountIds, array(
        'readonly' => true,
        'onclick' => 'jQuery("#coa-dialog").dialog("open"); return false;',
        'onkeypress' => 'if (event.keyCode == 13) { $("#coa-dialog").dialog("open"); return false; }'
    )); ?>
    <?php echo CHtml::openTag('span', array('id' => 'coa_name')); ?>
    <?php $coa = Account::model()->findByPk($account->id); ?>
    <?php echo CHtml::encode(CHtml::value($coa, 'name')); ?>
    <?php echo CHtml::closeTag('span'); ?> 

	<?php /*echo CHtml::dropDownlist('StartAccount', $startAccount,
		CHtml::listData($accounts, 'code', 'codeAndName'), 
		array(
			'empty'=>'-- Account --'
		)); ?>
	<?php echo CHtml::dropDownlist('EndAccount', $endAccount,
		CHtml::listData($accounts, 'code', 'codeAndName'), 
		array(
			'empty'=>'-- Account --'
		));*/ ?>
	
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
                        'selectableRows' => '10',
                    ),
                    'code',
                    'name',
                    array(
                        'name' => 'account_category_id',
                        'filter' => CHtml::activeDropDownList($account, 'account_category_id', CHtml::listData(AccountCategory::model()->findAll(array('order' => 'name')), 'id', 'name'), array('empty' => '-- All --')),
                        'value' => 'CHtml::value($data, "accountCategory.name")',
                    ),
                    'branch.code',
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