<div class="form">
    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Retur #', false); ?>
                <span id="purchase_return_codeNumber">
                    <?php echo CHtml::encode($purchaseReturn->header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchaseReturn->header,
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
                <?php echo CHtml::error($purchaseReturn->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', false); ?>
                <?php echo CHtml::activeTextArea($purchaseReturn->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($purchaseReturn->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">

            <?php
            $returnReceive = $purchaseReturn->header->receiveHeader(array(
                'scopes' => 'resetScope',
                'with' => array(
                    'purchaseHeader:resetScope' => array(
                        'with' => 'supplier:resetScope',
                    ),
                ),
            ));
            ?>

            <div class="row">
                <?php echo CHtml::label('Penerimaan #', ''); ?>
                <?php if ($purchaseReturn->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($purchaseReturn->header, 'receive_header_id', array('readonly' => true, 'onclick' => '$("#receive-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#receive-dialog").dialog("open"); return false; }')); ?>
                    <?php echo CHtml::openTag('span', array('id' => 'receive_code_number')); ?>
                    <?php echo CHtml::encode(($purchaseReturn->header->receiveHeader === null) ? '' : $purchaseReturn->header->receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($purchaseReturn->header, 'receive_header_id'); ?>

                    <?php
                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'receive-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'Penerimaan Barang',
                            'autoOpen' => false,
                            'width' => 'auto',
                            'modal' => true,
                        ),
                    ));
                    ?>
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'receive-grid',
                        'dataProvider' => $dataProvider,
                        'filter' => $receiveHeader,
                        'selectionChanged' => 'js:function(id) {
                            $("#' . CHtml::activeId($purchaseReturn->header, 'receive_header_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#receive-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "") {
                                $("#receive_code_number").html("");
                                $("#supplier_company").html("");
                                $("#purchase_return_codeNumber").html("");
                                $("#branch").html("");
                            } else {
                                $.ajax({
                                        type: "POST",
                                        dataType: "JSON",
                                        url: "' . CController::createUrl('ajaxHtmlReturnData', array('id' => $purchaseReturn->header->id)) . '",
                                        data: $("form").serialize(),
                                        success: function(data) {
                                                $("#receive_code_number").html(data.receive_code_number);
                                                $("#supplier_company").html(data.supplier_company);
                                                $("#purchase_return_codeNumber").html(data.purchase_return_codeNumber);
                                                $("#branch").html(data.branch);
                                        },
                                });
                            }
                            $.ajax({
                                    type: "POST",
                                    url: "' . CController::createUrl('ajaxHtmlAddReceive', array('id' => $purchaseReturn->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(html) { $("#detail_div").html(html); },
                            });
                        }',
                        'columns' => array(
                            array(
                                'name' => 'cn_ordinal',
                                'header' => 'Penerimaan #',
                                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($receiveHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; /' . ReceiveHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeDropDownList($receiveHeader, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                                '<div style="display: inline-block">' . CHtml::activeTextField($receiveHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                                'value' => '$data->getCodeNumber(ReceiveHeader::CN_CONSTANT)',
                                'htmlOptions' => array('style' => 'width: 200px'),
                            ),
                            array(
                                'header' => 'Tanggal',
                                'name' => 'date',
                                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
                            ),
                            array(
                                'header' => 'Supplier',
                                'name' => 'purchaseHeader.supplier_id',
                                'filter' => CHtml::textField('SupplierCompany', $supplierCompany, array('maxLength' => 60, 'size' => 10)),
                                'value' => 'CHtml::value($data, "purchaseHeader.supplier.company")',
                            ),
                        ),
                    ));
                    ?>
                    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                <?php else: ?>
                    <?php echo CHtml::activeHiddenField($purchaseReturn->header, 'receive_header_id'); ?>
                    <?php echo CHtml::encode($returnReceive->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Supplier', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_company')); ?>
                <?php echo CHtml::encode(CHtml::value($returnReceive, 'purchaseHeader.supplier.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Branch', false); ?>
                <?php echo CHtml::openTag('span', array('id' => 'branch')); ?>
                <?php echo CHtml::encode(CHtml::value($returnReceive, 'branch.name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Gudang', ''); ?>
                <?php echo CHtml::activeDropDownList($purchaseReturn->header, 'warehouse_id', CHtml::listData(Warehouse::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Warehouse --')); ?>
                <?php echo CHtml::error($purchaseReturn->header, 'warehouse_id'); ?>
            </div>

        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::error($purchaseReturn->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('purchaseReturn' => $purchaseReturn)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->
