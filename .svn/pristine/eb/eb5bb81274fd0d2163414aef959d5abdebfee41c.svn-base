<div class="form">

    <?php echo CHtml::beginForm(); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Pembayaran #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($salePayment->header->getCodeNumber(SalePaymentHeader::CN_CONSTANT)); ?>
                </span>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($salePayment->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($salePayment->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($salePayment->header, 'branch_id'); ?>
                <?php if ($salePayment->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($salePayment->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $salePayment->header->id)),
                            'success' => 'function(data) {
                                $("#code_number").html(data.codeNumber);
                            }',
                        )) . '$.fn.yiiGridView.update("sale-receipt-header-grid", {
                            data: $("form").serialize()
                        }); $.fn.yiiGridView.update("account-grid", {
                            data: $("form").serialize()
                        });',
                    ));
                    ?>
                    <?php echo CHtml::error($salePayment->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($salePayment->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal Pembayaran', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $salePayment->header,
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
                <?php echo CHtml::error($salePayment->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanda Terima Penjualan #', ''); ?>
                <?php if ($salePayment->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($salePayment->header, 'sale_receipt_header_id', array('readonly' => true, 'onclick' => '$("#sale-receipt-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#sale-receipt-header-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'sale_receipt_header_codeNumber')); ?>
                    <?php echo CHtml::encode(CHtml::value($salePayment->header, 'saleReceiptHeader.codeNumber')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($salePayment->header, 'sale_receipt_header_id'); ?>

                    <?php
                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'sale-receipt-header-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'Sale Receipt Header',
                            'autoOpen' => false,
                            'width' => 'auto',
                            'modal' => true,
                        ),
                    ));
                    ?>
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'sale-receipt-header-grid',
                        'dataProvider' => $saleReceiptDataProvider,
                        'filter' => $saleReceiptHeader,
                        'selectionChanged' => 'js:function(id) {
                            if ($.fn.yiiGridView.getSelection(id) != "")			//prevent deselect if user choose the same row
                                $("#' . CHtml::activeId($salePayment->header, 'sale_receipt_header_id') . '").val($.fn.yiiGridView.getSelection(id));

                            $("#sale-receipt-header-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) != "")
                            {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonReceipt', array('id' => $salePayment->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#sale_receipt_header_codeNumber").html(data.sale_receipt_header_codeNumber);
                                        $("#sale_payment_header_codeNumber").html(data.sale_payment_header_codeNumber);
                                        $("#sale_receipt_header_date").html(data.sale_receipt_header_date);
                                        $("#sale_receipt_header_customer").html(data.sale_receipt_header_customer);
                                        $("#branch").html(data.branch);
                                    },
                                });

                                $.ajax({
                                    type: "POST",
                                    url: "' . CController::createUrl('ajaxHtmlResetDetail', array('id' => $salePayment->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(html){
                                        $("#detail_div").html(html);
                                    }
                                });
                            }
                        }',
                        'columns' => array(
                            array(
                                'name' => 'cn_ordinal',
                                'header' => 'Tanda Terima Penjualan #',
                                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleReceiptHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; /' . SaleReceiptHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::textField('CnMonth', $cnMonth, array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeTextField($saleReceiptHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                                'value' => '$data->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)',
                                'htmlOptions' => array('style' => 'width: 200px'),
                            ),
                            array(
                                'header' => 'Tanggal',
                                'name' => 'date',
                                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                            ),
                            array(
                                'name' => 'customer_id',
                                'filter' => CHtml::listData(Customer::model()->findAll(array('order' => 'name ASC')), 'id', 'company'),
                                'value' => 'CHtml::value($data, "customer.company")',
                            ),
                            array(
                                'header' => 'Total',
                                'filter' => false,
                                'value' => 'Yii::app()->numberFormatter->format("#,##0.00", $data->totalInvoice)',
                                'htmlOptions' => array(
                                    'style' => 'text-align: right',
                                ),
                            ),
                            array(
                                'header' => 'Payment',
                                'filter' => false,
                                'value' => 'Yii::app()->numberFormatter->format("#,##0.00", $data->payment)',
                                'htmlOptions' => array(
                                    'style' => 'text-align: right',
                                ),
                            ),
                            array(
                                'header' => 'Remaining',
                                'filter' => false,
                                'value' => 'Yii::app()->numberFormatter->format("#,##0.00", $data->remaining)',
                                'htmlOptions' => array(
                                    'style' => 'text-align: right',
                                ),
                            ),
                        ),
                    ));
                    ?>
                <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode($salePayment->header->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?>
                <?php endif; ?>
            </div>

            <?php
            $salePaymentReceipt = $salePayment->header->saleReceiptHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'customer:resetScope',
                ),
            ));
            ?>

            <div class="row">
                <?php echo CHtml::activeLabelEx($salePayment->header, 'saleReceiptHeader.customer_id'); ?>
                <?php echo CHtml::openTag('span', array('id' => 'sale_receipt_header_customer')); ?>
                <?php echo CHtml::encode(CHtml::value($salePaymentReceipt, 'customer.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                <?php echo CHtml::encode(CHtml::value($salePaymentReceipt, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($salePayment->header, 'Tanggal Tanda Terima'); ?>
                <?php echo CHtml::openTag('span', array('id' => 'sale_receipt_header_date')); ?>
                <?php echo CHtml::encode(CHtml::value($salePaymentReceipt, 'date')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row buttons">
        <?php echo CHtml::button('Cari Akun', array('name' => 'Search', 'onclick' => '$("#account-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#account-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('AccountId'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::error($salePayment->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('salePayment' => $salePayment)); ?>
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
				url: "' . CController::createUrl('ajaxHtmlAddAccount', array('id' => $salePayment->header->id, 'nt' => $salePayment->header->is_non_tax)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
            'code',
            'name',
            'branch.name: Cabang',
        ),
    ));
    ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>
