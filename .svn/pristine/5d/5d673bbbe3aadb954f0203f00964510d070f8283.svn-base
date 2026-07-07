<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Pembelian #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($purchase->header->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?>
                </span>
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $purchase->header,
                    'attribute' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                )); ?>
                <?php echo CHtml::error($purchase->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Supplier', ''); ?>
                <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'model' => $purchase->header,
                    'attribute' => 'supplier_id',
//                    'sourceUrl' => CController::createUrl('/completion/supplier'),
                    'source' => 'js:function(request, response) {
                        var branchId = $("#' . CHtml::activeId($purchase->header, 'branch_id') . '").val();
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: "' . CController::createUrl('/completion/ajaxJsonSupplier') . '",
                            data: {"term": request["term"], "branchId": branchId},
                            success: function(data) {
                                response(data);
                            },
                            error: function(data) {
                                response("");
                            },
                        });
                    }',
                    //additional javascript options for the autocomplete plugin
                    'options' => array(
                        'minLength' => '2',
                        'select' => 'js:function(event, ui) {
                            $("#supplier_name").html(ui.item.id);
                        }',
                    )
                )); ?>
                <?php $purchaseSupplier = $purchase->header->supplier(array('scopes' => 'resetScope')); ?>
                <?php echo CHtml::openTag('span', array('id' => 'supplier_name')); ?>
                <?php echo CHtml::encode(CHtml::value($purchaseSupplier, 'company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($purchase->header, 'branch_id'); ?>
                    <?php echo CHtml::activeDropDownList($purchase->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array(
                        'empty' => '-- Pilih Perusahaan --',
                        'onchange' => '
                            if ($(this).val() == 4) {
                                $(".tax_type").hide();
                            } else {
                                $(".tax_type").show();
                            }
                        ' .
                        CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumberTaxTotal', array('id' => $purchase->header->id)),
                            'success' => 'function(data) {
                                $("#code_number").html(data.codeNumber);
                                $("#taxPercentage").html(data.taxPercentage);
                            }',
                        )),
                    )); ?>
                    <?php echo CHtml::error($purchase->header, 'branch_id'); ?>
            </div>
            
            <div class="tax_type">
                <div class="row">
                    <?php echo CHtml::activeLabelEx($purchase->header, 'Include / Exclude'); ?>
                    <?php echo CHtml::activeDropDownList($purchase->header, 'is_non_tax', array(
//                         PurchaseHeader::NON_TAX => PurchaseHeader::NON_TAX_LITERAL,
                         PurchaseHeader::INCLUDE_TAX => PurchaseHeader::INCLUDE_TAX_LITERAL,
                         PurchaseHeader::EXCLUDE_TAX => PurchaseHeader::EXCLUDE_TAX_LITERAL,
                    ), array('empty' => '-- Pilih Tipe PPN --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumberTaxTotal', array('id' => $purchase->header->id)),
                            'success' => 'function(data) {
                                $("#sub_total").html(data.subTotal);
                                $("#taxPercentage").html(data.taxPercentage);
                                $("#taxValue").html(data.taxValue);
                                $("#grand_total").html(data.grandTotal);
                            }',
                        )),
                    )); ?>
                    <?php echo CHtml::error($purchase->header, 'is_non_tax'); ?>
                </div>
            </div>
            
            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($purchase->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($purchase->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::button('Cari Barang', array('name' => 'Search', 'onclick' => '$("#search-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#search-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('ProductId'); ?>
    </div>
	
    <div class="row">
        <?php echo CHtml::error($purchase->header, 'error'); ?>
   </div>
	
    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('purchase' => $purchase)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->

<div>
    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'search-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Products',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    )); ?>

    <div class="search-form">
        <div id="search_div">
            <?php
            $this->renderPartial('//site/_search', array(
                'model' => $product,
                'specificationList' => array(),
                'listData' => $this->listData(),
                'action' => CHtml::normalizeUrl(array('create')),
            ));
            ?>
        </div>
    </div>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'product-grid',
        'dataProvider' => $dataProvider,
		'filter' => $product,
        'selectionChanged' => 'js:function(id) {
            $("#ProductId").val($.fn.yiiGridView.getSelection(id));
            $("#search-dialog").dialog("close");
            $.ajax({
                type: "POST",
                url: "' . CController::createUrl('ajaxHtmlAddProduct', array('id' => $purchase->header->id)) . '",
                data: $("form").serialize(),
                success: function(html) { $("#detail_div").html(html); },
            });
        }',
        'columns' => array(
            'name',
            array(
                'name' => 'unit_id',
                'filter' => CHtml::listData(Unit::model()->findAll(array('order' => 't.name')), 'id', 'name'),
                'value' => 'CHtml::value($data, "unit.name")',
            ),
            'size',
        ),
    )); ?>

    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>
