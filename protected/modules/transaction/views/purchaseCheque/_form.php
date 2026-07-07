<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Pengeluaran Giro #', false); ?>
                <span id="purchase_cheque_codeNumber">
                    <?php echo CHtml::encode($purchaseCheque->header->getCodeNumber(PurchaseCheque::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal Keluar', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchaseCheque->header,
                    'attribute' => 'issue_date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                ));
                ?>
                <?php echo CHtml::error($purchaseCheque->header, 'issue_date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Jatuh Tempo', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchaseCheque->header,
                    'attribute' => 'due_date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                ));
                ?>
                <?php echo CHtml::error($purchaseCheque->header, 'due_date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseCheque->header, 'branch_id'); ?>
                <?php if ($purchaseCheque->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($purchaseCheque->header, 'branch_id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $purchaseCheque->header->id)),
                            'success' => 'function(data) {
							$("#code_number").html(data.codeNumber);
						}',
                        )),
                    ));
                    ?>
                    <?php echo CHtml::error($purchaseCheque->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($purchaseCheque->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($purchaseCheque->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($purchaseCheque->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseCheque->header, 'Bank'); ?>
                <?php echo CHtml::activeDropDownList($purchaseCheque->header, 'account_id', CHtml::listData(Account::model()->findAll(array('condition' => 'account_category_id = 2')), 'id', 'name'), array('empty' => '-- Pilih Bank --')); ?>
                <?php echo CHtml::error($purchaseCheque->header, 'account_id'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Giro / Cek #', false); ?>
                <?php echo CHtml::activeTextField($purchaseCheque->header, 'cheque_number', array('size' => 50, 'maxLength' => 60)); ?>
                <?php echo CHtml::error($purchaseCheque->header, 'cheque_number'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Jumlah (Rp)', false); ?>
                <?php
                echo CHtml::activeTextField($purchaseCheque->header, 'amount', array(
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('AjaxJsonAmount', array('id' => $purchaseCheque->header->id)),
                        'success' => 'function(data) {
                            $("#amount").html(data.amount);
                        }',
                    )),
                ));
                ?>
                <div id="amount" style="text-align: left; font-size: smaller">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($purchaseCheque->header, 'amount'))); ?>
                </div>
                <?php echo CHtml::error($purchaseCheque->header, 'amount'); ?>
            </div>	

            <div class="row">
                <?php echo CHtml::label('Tanda Terima Faktur #', ''); ?>
                <?php echo CHtml::activeTextField($purchaseCheque->header, 'purchase_receipt_header_id', array('readonly' => true, 'onclick' => '$("#purchase-receipt-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#purchase-header-dialog").dialog("open"); return false; }')); ?>
                <?php echo CHtml::openTag('span', array('id' => 'purchase_receipt_header_codeNumber')); ?>
                <?php echo CHtml::closeTag('span'); ?>
                <?php echo CHtml::error($purchaseCheque->header, 'purchase_receipt_header_id'); ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                    'id' => 'purchase-receipt-header-dialog',
                    // additional javascript options for the dialog plugin
                    'options' => array(
                        'title' => 'Purchase Receipt',
                        'autoOpen' => false,
                        'width' => 'auto',
                        'modal' => true,
                    ),
                ));
                ?>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'purchase-receipt-header-grid',
                    'dataProvider' => $dataProvider,
                    'filter' => $purchaseReceiptHeader,
                    'selectionChanged' => 'js:function(id) {
                        $("#' . CHtml::activeId($purchaseCheque->header, 'purchase_receipt_header_id') . '").val($.fn.yiiGridView.getSelection(id));
                        $("#purchase-receipt-header-dialog").dialog("close");
                        if ($.fn.yiiGridView.getSelection(id) == "")
                        {
                            $("#purchase_receipt_header_codeNumber").html("");
                            $("#purchase_cheque_codeNumber").html("");
                            $("#purchase_receipt_header_date").html("");
                            $("#purchase_receipt_header_supplier").html("");
                            $("#branch").html("");
                        }
                        else
                        {
                            $.ajax({
                                type: "POST",
                                dataType: "JSON",
                                url: "' . CController::createUrl('AjaxJsonPurchaseReceipt', array('id' => $purchaseCheque->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(data) {
                                    $("#purchase_receipt_header_codeNumber").html(data.purchase_receipt_header_codeNumber);
                                    $("#purchase_cheque_codeNumber").html(data.purchase_cheque_codeNumber);
                                    $("#purchase_receipt_header_date").html(data.purchase_receipt_header_date);
                                    $("#purchase_receipt_header_supplier").html(data.purchase_receipt_header_supplier);
                                    $("#branch").html(data.branch);
                                },
                            });
                        }
                        $.ajax({
                            type: "POST",
                            url: "' . CController::createUrl('ajaxHtmlShowPurchaseReceipt', array('id' => $purchaseCheque->header->id)) . '",
                            data: $("form").serialize(),
                            success: function(html) { $("#detail_div").html(html); },
                        });
                    }',
                    'columns' => array(
                        array(
                            'name' => 'cn_ordinal',
                            'header' => 'Tanda Terima Pembelian #',
                            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchaseReceiptHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                            '<div style="display: inline-block"> &nbsp; /' . PurchaseReceiptHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                            '<div style="display: inline-block">' . CHtml::textField('CnMonth', $cnMonth, array('maxLength' => 4, 'size' => 2)) . '</div>' .
                            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                            '<div style="display: inline-block">' . CHtml::activeTextField($purchaseReceiptHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                            'value' => '$data->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)',
                            'htmlOptions' => array('style' => 'width: 200px'),
                        ),
                        array(
                            'header' => 'Tanggal',
                            'name' => 'date',
                            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                        ),
                        array(
                            'name' => 'supplier_id',
                            'filter' => CHtml::listData(Supplier::model()->findAll(), 'id', 'company'),
                            'value' => 'CHtml::value($data, "supplier.company")',
                        ),
                    ),
                ));
                ?>
                <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
            </div>
            <?php
            $purchaseChequeReceipt = $purchaseCheque->header->purchaseReceiptHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'supplier:resetScope',
                ),
            ));
            ?>
            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseCheque->header, 'Tanggal Faktur'); ?>
                <?php echo CHtml::openTag('span', array('id' => 'purchase_receipt_header_date')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseChequeReceipt, 'date')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseCheque->header, 'Supplier'); ?>
                <?php echo CHtml::openTag('span', array('id' => 'purchase_receipt_header_supplier')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseChequeReceipt, 'supplier.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseChequeReceipt, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('purchaseCheque' => $purchaseCheque, 'purchaseReceipt' => ($purchaseCheque->header->purchaseReceiptHeader === null) ? PurchaseReceiptHeader::model() : $purchaseCheque->header->purchaseReceiptHeader)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->