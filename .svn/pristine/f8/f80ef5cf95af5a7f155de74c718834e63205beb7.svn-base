<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Retur #', false); ?>
                <span id="sale_return_codeNumber">
                    <?php echo CHtml::encode($saleReturn->header->getCodeNumber(SaleReturnHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleReturn->header,
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
                <?php echo CHtml::error($saleReturn->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($saleReturn->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($saleReturn->header, 'note'); ?>
            </div>
        </div>

        <?php
        $returnInvoice = $saleReturn->header->saleInvoice(array(
            'scopes' => 'resetScope',
            'with' => array(
                'deliveryHeader:resetScope' => array(
                    'with' => 'saleHeader:resetScope',
                ),
            ),
        ));
        ?>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Invoice #', ''); ?>
                <?php if ($saleReturn->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($saleReturn->header, 'sale_invoice_id', array('readonly' => true, 'onclick' => '$("#invoice-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#invoice-header-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'sale_invoice_codeNumber')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleReturn->header, 'saleInvoice.codeNumber')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($saleReturn->header, 'sale_invoice_id'); ?>

                    <?php
                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'invoice-header-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'Invoice',
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
                            $("#' . CHtml::activeId($saleReturn->header, 'sale_invoice_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#invoice-header-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "")
                            {
                                $("#sale_invoice_codeNumber").html("");
                                $("#customer_company").html("");
                                $("#sale_return_codeNumber").html("");
                                $("#branch").html("");
                            }
                            else
                            {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonReturn', array('id' => $saleReturn->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#sale_invoice_codeNumber").html(data.sale_invoice_codeNumber);
                                        $("#customer_company").html(data.customer_company);
                                        $("#sale_return_codeNumber").html(data.sale_return_codeNumber);
                                        $("#branch").html(data.branch);
                                    },
                                });
                            }
                            $.ajax({
                                type: "POST",
                                url: "' . CController::createUrl('ajaxHtmlAddProduct', array('id' => $saleReturn->header->id, 'nt' => $saleReturn->header->is_non_tax)) . '",
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
                                '<div style="display: inline-block">' . CHtml::textField('CnMonth', $cnMonth, array('maxLength' => 4, 'size' => 2)) . '</div>' .
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
                                'header' => 'Customer',
                                'filter' => CHtml::textField('CustomerName', $customerName),
                                'value' => 'CHtml::value($data, "deliveryHeader.saleHeader.customer.company")',
                            ),
                            array(
                                'header' => 'No. PO',
                                'name' => 'reference',
                                'value' => 'CHtml::encode(CHtml::value($data, "reference"))'
                            ),
                        ),
                    ));
                    ?>
                    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                <?php else: ?>
                    <?php echo CHtml::activeHiddenField($saleReturn->header, 'sale_invoice_id'); ?>
                    <?php echo CHtml::encode($returnInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Customer', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_company')); ?>
                <?php echo CHtml::encode(CHtml::value($returnInvoice, 'deliveryHeader.saleHeader.customer.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                <?php echo CHtml::encode(CHtml::value($returnInvoice, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Gudang', ''); ?>
                <?php echo CHtml::activeDropDownList($saleReturn->header, 'warehouse_id', CHtml::listData(Warehouse::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Warehouse --')); ?>
                <?php echo CHtml::error($saleReturn->header, 'warehouse_id'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::error($saleReturn->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('saleReturn' => $saleReturn)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->
