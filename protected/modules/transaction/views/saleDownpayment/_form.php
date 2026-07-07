<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Uang Muka #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($saleDownpayment->header->getCodeNumber(SaleDownpayment::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleDownpayment->header,
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
                <?php echo CHtml::error($saleDownpayment->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($saleDownpayment->header, 'account_id'); ?>
                <?php echo CHtml::activeDropDownList($saleDownpayment->header, 'account_id', CHtml::listData(Account::model()->findAllByAttributes(array('branch_id' => CHtml::value($saleDownpayment->header, 'branch_id')), array('order' => 't.name', 'condition' => 'account_category_id IN (1, 2)', )), 'id', 'name'), array('empty' => '-- Pilih Akun --')); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'account_id'); ?>
            </div>
			
			<div class="row">
                <?php echo CHtml::label('Quantity', false); ?>
                <?php echo CHtml::activeTextField($saleDownpayment->header, 'quantity'); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'quantity'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($saleDownpayment->header, 'amount'); ?>
                <?php echo CHtml::activeTextField($saleDownpayment->header, 'amount', array(
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('AjaxJsonAmount', array('id' => $saleDownpayment->header->id)),
                        'success' => 'function(data) {
                            $("#amount").html(data.amount);
                        }',
                    )),
                ));
                ?>
                <div id="amount" style="text-align: left; font-size: smaller">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleDownpayment->header, 'amount'))); ?>
                </div>
                <?php echo CHtml::error($saleDownpayment->header, 'amount'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Pajak (%)', false); ?>
                <?php echo CHtml::activeTextField($saleDownpayment->header, 'tax'); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'tax'); ?>
            </div>
			<div class="row">
                <?php echo CHtml::label('Nomor Pajak', false); ?>
                <?php echo CHtml::activeTextField($saleDownpayment->header, 'tax_number'); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'tax_number'); ?>
            </div>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($saleDownpayment->header, 'branch_id'); ?>
            <?php if ($saleDownpayment->header->isNewRecord): ?>
                <?php echo CHtml::activeDropDownList($saleDownpayment->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => "JSON",
                        'url' => CController::createUrl('ajaxJsonCodeNumberAccount', array('id' => $saleDownpayment->header->id)),
                        'success' => 'function(data) {
                            $("#code_number").html(data.codeNumber);
                            $("#'.CHtml::activeId($saleDownpayment->header, 'account_id').'").html(data.accountOptions);
                        }',
                    )) . '$.fn.yiiGridView.update("customer-grid", {
                        data: $("form").serialize()
                    });',
                )); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'branch_id'); ?>
            <?php else: ?>
                <?php echo CHtml::encode(CHtml::value($saleDownpayment->header, 'branch.name')); ?>
            <?php endif;?>
        </div>
	
        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Customer', ''); ?>
                <?php echo CHtml::activeTextField($saleDownpayment->header, 'customer_id', array('readonly' => true, 'onclick' => '$("#customer-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#customer-dialog").dialog("open"); return false; }')); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'customer_id'); ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                    'id' => 'customer-dialog',
                    // additional javascript options for the dialog plugin
                    'options' => array(
                        'title' => 'Customer',
                        'autoOpen' => false,
                        'width' => 'auto',
                        'modal' => true,
                    ),
                ));
                ?>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'customer-grid',
                    'dataProvider' => $dataProvider,
                    'filter' => $customer,
                    'selectionChanged' => 'js:function(id) {
                        $("#' . CHtml::activeId($saleDownpayment->header, 'customer_id') . '").val($.fn.yiiGridView.getSelection(id));
                        $("#customer-dialog").dialog("close");
                        if ($.fn.yiiGridView.getSelection(id) == "")
                        {
                            $("#customer_id").html("");
                            $("#customer_company").html("");
                            $("#customer_address").html("");

                        }
                        else
                        {
                            $.ajax({
                                type: "POST",
                                dataType: "JSON",
                                url: "' . CController::createUrl('AjaxJsonCustomer', array('id' => $saleDownpayment->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(data) {
                                    $("#customer_id").html(data.customer_id);
                                    $("#customer_company").html(data.customer_company);
									$("#customer_address").html(data.customer_address);
                                },
                            });
                        }
                    }',
                    'columns' => array(
                        'company',
                        'name',
                        'address',
                        'phone',
                    ),
                ));
                ?>
                <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
            </div>
			
            <?php $saleDownpaymentCustomer = $saleDownpayment->header->customer(array('scopes' => 'resetScope')); ?>

            <div class="row">
                    <?php echo CHtml::label('Nama Pelanggan', ''); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'customer_company')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleDownpaymentCustomer, 'company')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                    <?php echo CHtml::label('Alamat Pelanggan', ''); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'customer_address')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleDownpaymentCustomer, 'address')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
            </div>
			
            <div class="row">
                <?php echo CHtml::activeLabelEx($saleDownpayment->header, 'Penanggung Jawab'); ?>
                <?php echo CHtml::activeDropDownList($saleDownpayment->header, 'board_id', CHtml::listData(Board::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Penanggung Jawab --')); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'board_id'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($saleDownpayment->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($saleDownpayment->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->
