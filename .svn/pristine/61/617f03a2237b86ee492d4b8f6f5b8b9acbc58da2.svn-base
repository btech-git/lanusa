<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($saleInvoice->header, ''); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Invoice #', false); ?>
                <span id="sale_invoice_codeNumber">
                    <?php echo CHtml::encode($saleInvoice->header->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?>
                </span>
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleInvoice->header,
                    'attribute' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                )); ?>
                <?php echo CHtml::error($saleInvoice->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($saleInvoice->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($saleInvoice->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Pengiriman #', ''); ?>
                <?php if ($saleInvoice->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($saleInvoice->header, 'delivery_header_id', array('readonly' => true, 'onclick' => '$("#delivery-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#delivery-header-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'delivery_header_codeNumber')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleInvoice->header, 'deliveryHeader.codeNumber')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($saleInvoice->header, 'delivery_header_id'); ?>

                    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'delivery-header-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'sale Items',
                            'autoOpen' => false,
                            'width' => 'auto',
                            'modal' => true,
                        ),
                    )); ?>
                
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'delivery-header-grid',
                        'dataProvider' => $dataProvider,
                        'filter' => $deliveryHeader,
                        'selectionChanged' => 'js:function(id) {
                            $("#' . CHtml::activeId($saleInvoice->header, 'delivery_header_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#delivery-header-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "") {
                                $("#delivery_header_codeNumber").html("");
                                $("#sale_invoice_codeNumber").html("");
                                $("#code_number").html("");
                                $("#customer_company").html("");
                                $("#branch").html("");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonDelivery', array('id' => $saleInvoice->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#delivery_header_codeNumber").html(data.delivery_header_codeNumber);
                                        $("#sale_invoice_codeNumber").html(data.sale_invoice_codeNumber);
                                        $("#code_number").html(data.code_number);
                                        $("#customer_company").html(data.customer_company);
                                        $("#branch").html(data.branch);
                                        $("#' . CHtml::activeId($saleInvoice->header, 'tax_percentage') . '").html(data.taxPercentage);
                                    },
                                });
                            }
                            $.ajax({
                                type: "POST",
                                url: "' . CController::createUrl('ajaxHtmlShowDelivery', array('id' => $saleInvoice->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(html) { $("#detail_div").html(html); },
                            });
                        }',
                        'columns' => array(
                            array(
                                'name' => 'cn_ordinal',
                                'header' => 'Penjualan #',
                                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($deliveryHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; /' . DeliveryHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeDropDownList($deliveryHeader, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeTextField($deliveryHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                                'value' => '$data->getCodeNumber(DeliveryHeader::CN_CONSTANT)',
                                'htmlOptions' => array('style' => 'width: 200px'),
                            ),
                            array(
                                'header' => 'Tanggal',
                                'name' => 'date',
                                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                            ),
                            array(
                                'name' => 'saleHeader.customer_id',
                                'filter' => CHtml::textField('CustomerCompany', $customerCompany, array('maxLength' => 60, 'size' => 10)),
                                'value' => 'CHtml::value($data, "saleHeader.customer.company")',
                            ),
                        ),
                    )); ?>
                    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode($saleInvoice->header->deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?>
                    <?php echo CHtml::activeHiddenField($saleInvoice->header, 'delivery_header_id', array('value' => $saleInvoice->header->delivery_header_id)); ?>
                <?php endif; ?>
            </div>

            <?php $saleInvoiceSale = $saleInvoice->header->deliveryHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'saleHeader:resetScope',
                ),
            )); ?>

            <div class="row">
                <?php echo CHtml::label('Customer', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_company')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleInvoiceSale, 'saleHeader.customer.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleInvoiceSale, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($saleInvoice->header, 'Nomor Faktur Pajak'); ?>
                <?php echo CHtml::activeTextField($saleInvoice->header, 'reference'); ?>
                <?php echo CHtml::error($saleInvoice->header, 'reference'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('saleInvoice' => $saleInvoice, 'delivery' => ($saleInvoice->header->deliveryHeader === null) ? DeliveryHeader::model() : $saleInvoice->header->deliveryHeader)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->
