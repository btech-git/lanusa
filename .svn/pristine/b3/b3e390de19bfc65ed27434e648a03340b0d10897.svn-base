<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Pengeluaran #', ''); ?>
                <span id="code_number">
                    <?php
                    $expenseHeaderConstant;
                    if ($expense->header->is_bank)
                        $expenseHeaderConstant = ExpenseHeader::CN_CONSTANT_BANK;
                    else
                        $expenseHeaderConstant = ExpenseHeader::CN_CONSTANT_CASH;

                    echo CHtml::encode($expense->header->getCodeNumber($expenseHeaderConstant));
                    ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', ''); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $expense->header,
                    'attribute' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                ));
                ?>
                <?php echo CHtml::error($expense->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($expense->header, 'branch_id'); ?>
                <?php if ($expense->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($expense->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumberAccount', array('id' => $expense->header->id)),
                            'success' => 'function(data) {
                                    $("#code_number").html(data.codeNumber);
                                    $("#' . CHtml::activeId($expense->header, 'account_id') . '").html(data.accountOptions);
                            }',
                        )) . '$.fn.yiiGridView.update("account-grid", {
                                data: $("form").serialize()
                        });',
                    ));
                    ?>
                    <?php echo CHtml::error($expense->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($expense->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($expense->header, 'account_id'); ?>
                <?php echo CHtml::activeDropDownList($expense->header, 'account_id', CHtml::listData(Account::model()->findAllByAttributes(array('branch_id' => $expense->header->branch_id), array('order' => 't.name', 'condition' => 'account_category_id IN (1, 2)')), 'id', 'name'), array('empty' => '-- Pilih Akun --')); ?>
                <?php echo CHtml::error($expense->header, 'account_id'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($expense->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($expense->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row buttons">
        <?php echo CHtml::button('Cari Akun', array('name' => 'Search', 'onclick' => '$("#account-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#account-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('AccountId'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::error($expense->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('expense' => $expense)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->

<div>
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'account-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Accounts',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    ));
    ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'account-grid',
        'dataProvider' => $accountDataProvider,
        'filter' => $account,
        'selectionChanged' => 'js:function(id) {
			$("#AccountId").val($.fn.yiiGridView.getSelection(id));
			$("#account-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('AjaxHtmlAddAccount', array('id' => $expense->header->id, 'nt' => $expense->header->is_non_tax)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
            'code: Kode',
            'name:nama Akun',
            'branch.name: Cabang',
        ),
    ));
    ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>