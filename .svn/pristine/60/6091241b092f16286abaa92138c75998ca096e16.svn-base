<div class="form">

    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($purchaseReceipt->header); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Tanda Terima Pembelian #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($purchaseReceipt->header->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchaseReceipt->header,
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
                <?php echo CHtml::error($purchaseReceipt->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', false); ?>
                <?php echo CHtml::activeTextArea($purchaseReceipt->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($purchaseReceipt->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseReceipt->header, 'branch_id'); ?>
                <?php if ($purchaseReceipt->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($purchaseReceipt->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $purchaseReceipt->header->id)),
                            'success' => 'function(data) {
                                $("#code_number").html(data.codeNumber);
                            }',
                        )) . '$.fn.yiiGridView.update("supplier-grid", {
                            data: $("form").serialize()
                        }); $.fn.yiiGridView.update("receive-grid", {
                            data: $("form").serialize()
                        });',
                    ));
                    ?>
                    <?php echo CHtml::error($purchaseReceipt->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($purchaseReceipt->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

                <?php if ($purchaseReceipt->header->isNewRecord): ?>
                <div class="row">
                    <?php echo CHtml::label('Supplier', ''); ?>
                    <?php
                    echo CHtml::activeTextField($purchaseReceipt->header, 'supplier_id', array(
                        'readonly' => true,
                        'onclick' => '$("#supplier-dialog").dialog("open"); return false;',
                        'onkeypress' => 'if (event.keyCode == 13) { $("#supplier-dialog").dialog("open"); return false; }'));
                    ?>
                    <?php echo CHtml::openTag('span', array('id' => 'supplier_id')); ?>
                    <?php echo CHtml::encode(CHtml::value($purchaseReceipt->header, 'supplier.company')); ?>
                    <?php echo CHtml::closeTag('span'); ?>
                    <?php echo CHtml::error($purchaseReceipt->header, 'supplier_id'); ?>

                    <?php
                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id' => 'supplier-dialog',
                        // additional javascript options for the dialog plugin
                        'options' => array(
                            'title' => 'Supplier',
                            'autoOpen' => false,
                            'width' => 'auto',
                            'modal' => true,
                        ),
                    ));
                    ?>
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'supplier-grid',
                        'dataProvider' => $supplierDataProvider,
                        'filter' => $supplier,
                        'selectionChanged' => 'js:function(id) {
                            $("#' . CHtml::activeId($purchaseReceipt->header, 'supplier_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#supplier-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "")
                            {
                                $("#supplier_id").html("");
                                $("#supplier_name").html("");
                                $("#supplier_address").html("");
                            }
                            else
                            {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('AjaxJsonSupplier', array('id' => $purchaseReceipt->header->id)) . '",
                                    data: $("form").serialize(),
                                    success: function(data) {
                                        $("#supplier_id").html(data.supplier_id);
                                        $("#supplier_name").html(data.supplier_name);
                                        $("#supplier_address").html(data.supplier_address);
                                    },
                                });
                            }
                            $.ajax({
                                type: "POST",
                                url: "' . CController::createUrl('ajaxHtmlResetDetail', array('id' => $purchaseReceipt->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(html) { $("#detail_div").html(html); },
                            });

                            //update the receive grid with supplier filter
                            $.fn.yiiGridView.update("receive-grid", {
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
            <?php endif; ?>

            <?php $purchaseReceiptSupplier = $purchaseReceipt->header->supplier(array('scopes' => 'resetScope')); ?>

            <div class="row">
                <?php echo CHtml::label('Nama Supplier', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_name')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseReceiptSupplier, 'name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Alamat Supplier', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_address')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseReceiptSupplier, 'address')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::button('Tambah Penerimaan', array('name' => 'Search', 'onclick' => '$("#receive-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#receive-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('ReceiveId'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::error($purchaseReceipt->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('purchaseReceipt' => $purchaseReceipt)); ?>
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
        'id' => 'receive-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Data Penerimaan Barang',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    ));
    ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'receive-grid',
        'dataProvider' => $receiveDataProvider,
        'filter' => $receive,
        'selectionChanged' => 'js:function(id) {
			$("#ReceiveId").val($.fn.yiiGridView.getSelection(id));
			$("#receive-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('ajaxHtmlAddReceive', array('id' => $purchaseReceipt->header->id)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
            array(
                'name' => 'cn_ordinal',
                'header' => 'Penerimaan #',
                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($receive, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                '<div style="display: inline-block"> &nbsp; /' . ReceiveHeader::CN_CONSTANT . '/ &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeDropDownList($receive, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeTextField($receive, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
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
                'filter' => false,
                'value' => 'CHtml::value($data, "purchaseHeader.supplier.company")',
            ),
            array(
                'header' => 'Total',
                'filter' => false,
                'value' => 'number_format($data->grandTotalReceipt, 2)',
                'htmlOptions' => array('style' => 'text-align: right'),
            ),
        ),
    ));
    ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>