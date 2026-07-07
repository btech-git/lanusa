<div class="form">

    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($model); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Tanggal', ''); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
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
                <?php echo CHtml::error($model, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($model, 'customer_id'); ?>
                <?php echo CHtml::activeTextField($model, 'customer_id', array(
                    'readonly' => true, 
                    'onclick' => '$("#customer-dialog").dialog("open"); return false;', 
                    'onkeypress' => 'if (event.keyCode == 13) { $("#customer-dialog").dialog("open"); return false; }'
                )); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_company')); ?>
                    <?php echo CHtml::encode(CHtml::value($model, 'customer.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
                <?php echo CHtml::error($model, 'customer_id'); ?>
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabelEx($model, 'branch_id'); ?>
                <?php if ($model->isNewRecord): ?>
                    <?php echo CHtml::activeDropDownList($model, 'branch_id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => '$.fn.yiiGridView.update("account-grid", {
                            data: $("form").serialize()
                        });',
                    )); ?>
                    <?php echo CHtml::error($model, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($model, 'branch.name')); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($model, 'note'); ?>
                <?php echo CHtml::activeTextArea($model, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($model, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div>
        <table>
            <tr>
                <td><?php echo CHtml::activeLabelEx($model, 'Akun Bank'); ?></td>
                <td><?php echo CHtml::activeLabelEx($model, 'Jumlah Terima (Rp)'); ?></td>
                <td colspan="2"><?php echo CHtml::activeLabelEx($model, 'PPn'); ?></td>
                <td colspan="2"><?php echo CHtml::activeLabelEx($model, 'PPh 23'); ?></td>
            </tr>
            <tr>
                <td>
                    <?php echo CHtml::activeDropDownList($model, 'account_id', CHtml::listData(Account::model()->findAll(array(
                        'order' => 't.name', 
                        'condition' => 'account_category_id IN (1, 2) AND t.is_inactive = 0'
                    )), 'id', 'name'), array('empty' => '-- Pilih Akun --')); ?>
                    <?php echo CHtml::error($model, 'account_id'); ?>
                </td>
                <td>
                    <?php echo CHtml::activeTextField($model, 'fee_amount'); ?>
                    <?php echo CHtml::error($model, 'fee_amount'); ?>
                </td>
                <td>
                    <?php echo CHtml::activeHiddenField($model, 'tax_item_amount'); ?>
                    <?php echo CHtml::activeDropDownList($model, 'tax_item_value', array(
                        0 => 0,
                        11 => 11,
                        12 => 12,
                    ), array(
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => 'JSON',
                            'url' => CController::createUrl('ajaxJsonTaxAmount', array('id' => $model->id)),
                            'success' => 'function(data) {
                                $("#' . CHtml::activeId($model, 'tax_item_amount') . '").val(data.tax_item_amount);
                                $("#tax_item_formatted").html(data.tax_item_formatted);
                            }',
                        )),
                    )); ?>%
                    <?php echo CHtml::error($model, 'tax_item_value'); ?>
                </td>
                <td>
                    <?php echo CHtml::openTag('span', array('id' => 'tax_item_formatted')); ?>
                        <?php echo CHtml::encode(CHtml::value($model, 'tax_item_amount')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                </td>
                <td>
                    <?php echo CHtml::activeHiddenField($model, 'tax_service_amount'); ?>
                    <?php echo CHtml::activeDropDownList($model, 'tax_service_value', array(
                        0 => 0,
                        2 => 2,
                    ), array(
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => 'JSON',
                            'url' => CController::createUrl('ajaxJsonTaxAmount', array('id' => $model->id)),
                            'success' => 'function(data) {
                                $("#' . CHtml::activeId($model, 'tax_service_amount') . '").val(data.tax_service_amount);
                                $("#tax_service_formatted").html(data.tax_service_formatted);
                            }',
                        )),
                    )); ?>%
                    <?php echo CHtml::error($model, 'tax_service_value'); ?>
                </td>
                <td>
                    <?php echo CHtml::openTag('span', array('id' => 'tax_service_formatted')); ?>
                        <?php echo CHtml::encode(CHtml::value($model, 'tax_service_amount')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->

<div>
    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'customer-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'sale Items',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    )); ?>

    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'customer-grid',
        'dataProvider' => $dataProvider,
        'filter' => $customer,
        'selectionChanged' => 'js:function(id) {
            $("#' . CHtml::activeId($model, 'customer_id') . '").val($.fn.yiiGridView.getSelection(id));
            $("#customer-dialog").dialog("close");
            if ($.fn.yiiGridView.getSelection(id) == "") {
                $("#customer_company").html("");
            } else {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "' . CController::createUrl('ajaxJsonCustomer') . '",
                    data: $("form").serialize(),
                    success: function(data) {
                        $("#customer_company").html(data.customer_company);
                    },
                });
            }
        }',
        'columns' => array(
            'company',
            'name', 
            'npwp',
            'branch.code',
        ),
    )); ?>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>