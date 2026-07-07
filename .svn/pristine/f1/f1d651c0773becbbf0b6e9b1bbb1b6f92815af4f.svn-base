<div class="form">

    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($receive->header); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Penerimaan #', false); ?>
                <span id="receive_header_codeNumber">
                    <?php echo CHtml::encode($receive->header->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?>
                </span>
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $receive->header,
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
                <?php echo CHtml::error($receive->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($receive->header, 'No SJ Supplier #'); ?>
                <?php echo CHtml::activeTextField($receive->header, 'reference'); ?>
                <?php echo CHtml::error($receive->header, 'reference'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($receive->header, 'Faktur Pajak Supplier #'); ?>
                <?php echo CHtml::activeTextField($receive->header, 'supplier_tax_number'); ?>
                <?php echo CHtml::error($receive->header, 'supplier_tax_number'); ?>
            </div>

        </div>

        <?php
        $receivePurchase = $receive->header->purchaseHeader(array(
            'scopes' => 'resetScope',
            'with' => 'supplier:resetScope',
        ));
        ?>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Pembelian #', ''); ?>
                <?php if ($receive->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($receive->header, 'purchase_header_id', array('readonly' => true, 'onclick' => '$("#purchase-header-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#purchase-header-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'purchase_header_codeNumber')); ?>
                    <?php echo CHtml::encode(CHtml::value($receive->header, 'purchaseHeader.codeNumber')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($receive->header, 'purchase_header_id'); ?>

                    <?php
                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'purchase-header-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'Purchase Order',
                            'autoOpen' => false,
                            'width' => 'auto',
                            'modal' => true,
                        ),
                    ));
                    ?>
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'purchase-header-grid',
                        'dataProvider' => $dataProvider,
                        'filter' => $purchaseHeader,
                        'selectionChanged' => 'js:function(id) {
                            $("#' . CHtml::activeId($receive->header, 'purchase_header_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#purchase-header-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "") {
                                $("#purchase_header_codeNumber").html("");
                                $("#receive_header_codeNumber").html("");
                                $("#supplier_company").html("");
                                $("#branch").html("");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonPurchase', array('id' => $receive->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#purchase_header_codeNumber").html(data.purchase_header_codeNumber);
                                        $("#receive_header_codeNumber").html(data.receive_header_codeNumber);
                                        $("#supplier_company").html(data.supplier_company);
                                        $("#branch").html(data.branch);
                                    },
                                });
                            }
                            $.ajax({
                                    type: "POST",
                                    url: "' . CController::createUrl('ajaxHtmlAddProduct', array('id' => $receive->header->id, 'nt' => $receive->header->is_non_tax)) . '",
                                    data: $("form").serialize(),
                                    success: function(html) { $("#detail_div").html(html); },
                            });
                        }',
                        'columns' => array(
                            array(
                                'name' => 'cn_ordinal',
                                'header' => 'Pembelian #',
                                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchaseHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; /' . PurchaseHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeDropDownList($purchaseHeader, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeTextField($purchaseHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                                'value' => '$data->getCodeNumber(PurchaseHeader::CN_CONSTANT)',
                                'htmlOptions' => array('style' => 'width: 200px'),
                            ),
                            array(
                                'header' => 'Tanggal',
                                'name' => 'date',
                                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                            ),
                            array(
                                'header' => 'Supplier',
                                'filter' => CHtml::textField('SupplierCompany', $supplierCompany, array('maxLength' => 60, 'size' => 10)),
                                'value' => 'CHtml::value($data, "supplier.company")',
                            ),
                            array(
                                'name' => 'branch_id',
                                'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 'name ASC')), 'id', 'name'),
                                'value' => 'CHtml::value($data, "branch.name")',
                            ),
                        ),
                    ));
                    ?>
                    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode($receivePurchase->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?>
                    <?php echo CHtml::activeHiddenField($receive->header, 'purchase_header_id'); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Supplier', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_company')); ?>
                <?php echo CHtml::encode(CHtml::value($receivePurchase, 'supplier.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                <?php echo CHtml::encode(CHtml::value($receivePurchase, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($receive->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($receive->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::error($receive->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('receive' => $receive)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->

