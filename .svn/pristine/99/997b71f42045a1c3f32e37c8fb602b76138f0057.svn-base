<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($delivery->header); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Pengiriman #', false); ?>
                <span id="delivery_header_codeNumber">
                    <?php echo CHtml::encode($delivery->header->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $delivery->header,
                    'attribute' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                )); ?>
                <?php echo CHtml::error($delivery->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($delivery->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($delivery->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <?php $deliverySale = $delivery->header->saleHeader(array(
                'scopes' => 'resetScope',
                'with' => 'customer:resetScope',
            )); ?>

            <div class="span-12 last">
                <div class="row">
                    <?php echo CHtml::label('Penjualan #', ''); ?>
                    <?php if ($delivery->header->isNewRecord): ?>
                        <?php echo CHtml::activeTextField($delivery->header, 'sale_header_id', array('readonly' => true, 'onclick' => '$("#sale-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#sale-header-dialog").dialog("open"); return false; }')); ?>
                        <?php echo CHtml::openTag('span', array('id' => 'sale_header_codeNumber')); ?>
                        <?php echo CHtml::encode(CHtml::value($delivery->header, 'saleHeader.codeNumber')); ?>
                        <?php echo CHtml::closeTag('span'); ?>
                        <?php echo CHtml::error($delivery->header, 'sale_header_id'); ?>

                        <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                            'id' => 'sale-header-dialog',
                            // additional javascript options for the dialog plugin
                            'options' => array(
                                'title' => 'Sale Order',
                                'autoOpen' => false,
                                'width' => 'auto',
                                'modal' => true,
                            ),
                        )); ?>
                    
                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'sale-header-grid',
                            'dataProvider' => $saleDataProvider,
                            'filter' => $saleHeader,
                            'selectionChanged' => 'js:function(id) {
                                $("#' . CHtml::activeId($delivery->header, 'sale_header_id') . '").val($.fn.yiiGridView.getSelection(id));
                                $("#sale-header-dialog").dialog("close");
                                
                                if ($.fn.yiiGridView.getSelection(id) == "") {
                                    $("#sale_header_codeNumber").html("");
                                    $("#customer_company").html("");
                                    $("#delivery_header_codeNumber").html("");
                                    $("#branch").html("");
                                    $("#reference").html("");
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        dataType: "JSON",
                                        url: "' . CController::createUrl('ajaxJsonSale', array('id' => $delivery->header->id)) . '",
                                        data: $("form").serialize(),
                                        success: function(data) {
                                            $("#sale_header_codeNumber").html(data.sale_header_codeNumber);
                                            $("#delivery_header_codeNumber").html(data.delivery_header_codeNumber);
                                            $("#customer_company").html(data.customer_company);
                                            $("#branch").html(data.branch);
                                            $("#reference").html(data.reference);
                                        },
                                    });
                                }
                                $.ajax({
                                    type: "POST",
                                    url: "' . CController::createUrl('ajaxHtmlAddProduct', array('id' => $delivery->header->id, 'nt' => $delivery->header->is_non_tax)) . '",
                                    data: $("form").serialize(),
                                    success: function(html) { $("#detail_div").html(html); },
                                });
                            }',
                            'columns' => array(
                                array(
                                    'name' => 'cn_ordinal',
                                    'header' => 'Penjualan #',
                                    'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                    '<div style="display: inline-block"> &nbsp; /' . SaleHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                                    '<div style="display: inline-block">' . CHtml::activeDropDownList($saleHeader, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                                    '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                                    '<div style="display: inline-block">' . CHtml::activeTextField($saleHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                                    'value' => '$data->getCodeNumber(SaleHeader::CN_CONSTANT)',
                                    'htmlOptions' => array('style' => 'width: 200px'),
                                ),
                                array(
                                    'header' => 'Tanggal',
                                    'name' => 'date',
                                    'filter' => false,
                                    'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                                ),
                                array(
                                    'header' => 'Customer',
                                    'name' => 'customer_id',
                                    'filter' => CHtml::textField('CustomerCompany', $customerCompany, array('maxLength' => 60, 'size' => 10)),
                                    'value' => 'CHtml::value($data, "customer.company")',
                                ),
                                'reference',
                                array(
                                    'name' => 'branch_id',
                                    'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
                                    'value' => 'CHtml::value($data, "branch.name")',
                                ),
                            ),
                        )); ?>
                        <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                    <?php else: ?>
                        <?php echo CHtml::encode($deliverySale->getCodeNumber(SaleHeader::CN_CONSTANT)); ?>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php echo CHtml::label('Customer', false); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'customer_company')); ?>
                    <?php echo CHtml::encode(CHtml::value($deliverySale, 'customer.company')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                </div>

                <div class="row">
                    <?php echo CHtml::label('Branch', false); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                    <?php echo CHtml::encode(CHtml::value($deliverySale, 'branch.name')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                </div>
            </div>
        </div>

        <hr />

        <div id="detail_div">
            <?php $this->renderPartial('_detail', array('delivery' => $delivery)); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
        </div>
        <?php echo IdempotentManager::generate(); ?>

        <?php echo CHtml::endForm(); ?>
    </div>
</div><!-- form -->
