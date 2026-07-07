<div class="form">
    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Penerimaan Faktur #', false); ?>
                <span id="purchase_invoice_codeNumber">
                        <?php echo CHtml::encode($purchaseInvoice->header->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchaseInvoice->header,
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
                <?php echo CHtml::error($purchaseInvoice->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($purchaseInvoice->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($purchaseInvoice->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($purchaseInvoice->header, 'branch_id'); ?>
                <?php if ($purchaseInvoice->header->isNewRecord): ?>
                    <?php echo CHtml::activeDropDownList($purchaseInvoice->header, 'branch_id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $purchaseInvoice->header->id)),
                            'success' => 'function(data) {
                                    $("#purchase_invoice_codeNumber").html(data.codeNumber);
                            }',
                        )) . '$.fn.yiiGridView.update("supplier-grid", {
                            data: $("form").serialize()
                        }); $.fn.yiiGridView.update("purchase-grid", {
                            data: $("form").serialize()
                        });',
                    )); ?>
                    <?php echo CHtml::error($purchaseInvoice->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($purchaseInvoice->header, 'branch.name')); ?>
                <?php endif;?>
            </div>

            <div class="row">
                <?php echo CHtml::label('No. Faktur Supplier', ''); ?>
                <?php echo CHtml::activeTextField($purchaseInvoice->header, 'reference'); ?>
                <?php echo CHtml::error($purchaseInvoice->header, 'reference'); ?>
            </div>
			
            <div class="row">
                <?php echo CHtml::label('Supplier', ''); ?>
                <?php echo CHtml::activeTextField($purchaseInvoice->header, 'supplier_id', array('readonly' => true, 'onclick' => '$("#supplier-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#supplier-dialog").dialog("open"); return false; }')); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_id')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseInvoice->header, 'supplier.company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
                <?php echo CHtml::error($purchaseInvoice->header, 'supplier_id'); ?>

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
                        $("#' . CHtml::activeId($purchaseInvoice->header, 'supplier_id') . '").val($.fn.yiiGridView.getSelection(id));
                        $("#supplier-dialog").dialog("close");
                        if ($.fn.yiiGridView.getSelection(id) == "") {
                            $("#supplier_id").html("");
                            $("#supplier_name").html("");
                            $("#supplier_address").html("");

                        } else {
                            $.ajax({
                                type: "POST",
                                dataType: "JSON",
                                url: "' . CController::createUrl('ajaxJsonSupplier', array('id' => $purchaseInvoice->header->id)) . '",
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
                            url: "' . CController::createUrl('ajaxHtmlResetDetail', array('id' => $purchaseInvoice->header->id)) . '",
                            data: $("form").serialize(),
                            success: function(html) { $("#detail_div").html(html); },
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
			
            <?php $purchaseInvoiceSupplier = $purchaseInvoice->header->supplier(array('scopes' => 'resetScope')); ?>

            <div class="row">
                <?php echo CHtml::label('Nama Supplier', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_name')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseInvoiceSupplier, 'name')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Alamat Supplier', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_address')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseInvoiceSupplier, 'address')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />
	
    <div class="row">
        <?php echo CHtml::button('Tambah Order Pembelian', array('name' => 'Search', 'onclick' => '$("#purchase-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#purchase-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('PurchaseHeaderId'); ?>
    </div>
	
    <div class="row">
        <?php echo CHtml::error($purchaseInvoice->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('purchaseInvoice' => $purchaseInvoice)); ?>
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
        'id' => 'purchase-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Order Pembelian',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    ));
    ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'purchase-grid',
        'dataProvider' => $dataProvider,
        'filter' => $purchaseHeader,
        'selectionChanged' => 'js:function(id) {
			$("#PurchaseHeaderId").val($.fn.yiiGridView.getSelection(id));
			$("#purchase-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('ajaxHtmlAddPurchase', array('id' => $purchaseInvoice->header->id)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
			array(
                'name' => 'cn_ordinal',
                'header' => 'Penerimaan Faktur #',
                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchaseHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
							'<div style="display: inline-block"> &nbsp; /' . PurchaseHeader::CN_CONSTANT . '/ &nbsp; </div>' .
							'<div style="display: inline-block">' . CHtml::textField('CnMonth', $cnMonth, array('maxLength' => 4, 'size' => 2)) . '</div>' .
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
                'name' => 'supplier_id',
                'header' => 'Supplier',
                'filter' => CHtml::listData(Supplier::model()->findAll(), 'id', 'company'),
                'value' => 'CHtml::value($data, "supplier.company")',
            ),
            array(
                'header' => 'Total',
                'filter' => false,
                'value' => 'number_format($data->grandTotal, 2)',
				'htmlOptions' => array('style' => 'text-align: right'),
            ),
        ),
    ));
    ?>

	<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>