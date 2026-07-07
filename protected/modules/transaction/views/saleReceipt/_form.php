<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Tanda Terima Penjualan #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($saleReceipt->header->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?>
                </span>
            </div>

            <div class="row">
                <?php echo CHtml::label('Date', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleReceipt->header,
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
                <?php echo CHtml::error($saleReceipt->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', false); ?>
                <?php echo CHtml::activeTextArea($saleReceipt->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($saleReceipt->header, 'note'); ?>
            </div>
        </div>
        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Jatuh Tempo', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleReceipt->header,
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
                <?php echo CHtml::error($saleReceipt->header, 'due_date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($saleReceipt->header, 'branch_id'); ?>
                <?php if ($saleReceipt->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($saleReceipt->header, 'branch_id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $saleReceipt->header->id)),
                            'success' => 'function(data) {
                                $("#code_number").html(data.codeNumber);
                            }',
                        )) . '
                        $.fn.yiiGridView.update("customer-grid", {
                                data: $("form").serialize()
                        });
                        ',
                    ));
                    ?>
                    <?php echo CHtml::error($saleReceipt->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($saleReceipt->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Customer', ''); ?>
                <?php if ($saleReceipt->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($saleReceipt->header, 'customer_id', array('readonly' => true, 'onclick' => '$("#customer-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#customer-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'customer_id')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleReceipt->header, 'customer.company')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($saleReceipt->header, 'customer_id'); ?>

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
                        'dataProvider' => $customerDataProvider,
                        'filter' => $customer,
                        'selectionChanged' => 'js:function(id) {
                            $("#' . CHtml::activeId($saleReceipt->header, 'customer_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#customer-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "")
                            {
                                $("#customer_id").html("");
                                $("#customer_name").html("");
                                $("#customer_address").html("");
                            }
                            else
                            {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonCustomer', array('id' => $saleReceipt->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#customer_id").html(data.customer_id);
                                        $("#customer_name").html(data.customer_name);
                                        $("#customer_address").html(data.customer_address);
                                    },
                                });
                            }
                            $.ajax({
                                type: "POST",
                                url: "' . CController::createUrl('ajaxHtmlResetDetail', array('id' => $saleReceipt->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(html) { $("#detail_div").html(html); },
                            });

                            //update sale invoice with customer filter
                            $.fn.yiiGridView.update("invoice-header-grid", {
                                    data: $("form").serialize()
                            });
                        }',
                        'columns' => array(
                            'name',
                            'company',
                            'address',
                        ),
                    ));
                    ?>
                <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                </div>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($saleReceipt->header, 'customer.company')); ?>
                <?php endif; ?>

                <?php $saleReceiptCustomer = $saleReceipt->header->customer(array('scopes' => 'resetScope')); ?>

            <div class="row">
                <?php echo CHtml::label('Nama Pelanggan', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_name')); ?>
                <?php echo CHtml::encode(CHtml::value($saleReceiptCustomer, 'name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Alamat Pelanggan', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_address')); ?>
                <?php echo CHtml::encode(CHtml::value($saleReceiptCustomer, 'address')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::button('Add Invoice', array('name' => 'Search', 'onclick' => '$("#invoice-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#invoice-header-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('SaleInvoiceId'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::error($saleReceipt->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('saleReceipt' => $saleReceipt)); ?>
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
        'id' => 'invoice-header-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Invoice Header',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    ));
    ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'invoice-header-grid',
        'dataProvider' => $saleInvoiceDataProvider,
        'filter' => $saleInvoice,
        'selectionChanged' => 'js:function(id) {
			$("#SaleInvoiceId").val($.fn.yiiGridView.getSelection(id));
			$("#invoice-header-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('AjaxHtmlAddInvoice', array('id' => $saleReceipt->header->id)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
            array(
                'name' => 'cn_ordinal',
                'header' => 'Invoice #',
                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleInvoice, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                '<div style="display: inline-block"> &nbsp; /' . SaleInvoice::CN_CONSTANT . '/ &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeDropDownList($saleInvoice, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeTextField($saleInvoice, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                'value' => '$data->getCodeNumber(SaleInvoice::CN_CONSTANT)',
                'htmlOptions' => array('style' => 'width: 200px'),
            ),
            array(
                'header' => 'Tanggal',
                'name' => 'date',
                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
            ),
            array(
                'name' => 'delivery_header_id',
                'header' => 'Pengiriman #',
                'filter' => false, //CHtml::listData(DeliveryHeader::model()->findAll(array('order' => 't.branch_id, t.cn_year, t.cn_month, t.cn_ordinal')), 'id', 'codeNumber'),
                'value' => 'CHtml::value($data, "deliveryHeader.codeNumber")',
            ),
            array(
                'header' => 'Pelanggan',
                'filter' => false, //CHtml::dropDownList('CustomerId', $customerId, CHtml::listData(Customer::model()->findAll(array('order' => 't.name ASC')), 'id', 'company'), array('empty' => '')),
                'value' => 'CHtml::value($data, "deliveryHeader.saleHeader.customer.company")',
            ),
            array(
                'header' => 'Total',
                'filter' => false,
                'value' => 'Yii::app()->numberFormatter->format("#,##0.00", $data->grandTotal)',
                'htmlOptions' => array(
                    'style' => 'text-align: right',
                ),
            ),
        ),
    ));
    ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>
