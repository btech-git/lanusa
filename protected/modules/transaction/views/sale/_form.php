<div class="form">

    <?php echo CHtml::beginForm(); ?>
<?php echo CHtml::errorSummary($sale->header); ?>
    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Penjualan #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($sale->header->getCodeNumber(SaleHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $sale->header,
                    'attribute' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                )); ?>
                <?php echo CHtml::error($sale->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($sale->header, 'branch_id'); ?>
                <?php echo CHtml::activeDropDownList($sale->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => "JSON",
                        'url' => CController::createUrl('ajaxJsonCodeNumberTaxTotal', array('id' => $sale->header->id)),
                        'success' => 'function(data) {
                            $("#code_number").html(data.codeNumber);
                            $("#' . CHtml::activeId($sale->header, 'tax') . '").html(data.taxPercentage);
                            $("#taxValue").html(data.taxValue);
                            $("#grand_total").html(data.grandTotal);
                        }',
                        'update' => '#detail_div',
                    )),
                )); ?>
                <?php echo CHtml::error($sale->header, 'branch_id'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($sale->header, 'employee_id_salesman'); ?>
                <?php echo CHtml::activeDropDownList($sale->header, 'employee_id_salesman', CHtml::listData(Employee::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Salesman --',)); ?>
                <?php echo CHtml::error($sale->header, 'employee_id_salesman'); ?>
            </div>
        </div>

        <div class="span-12 last">

            <div class="row">
                <?php echo CHtml::activeLabelEx($sale->header, 'customer_id'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'model' => $sale->header,
                    'attribute' => 'customer_id',
                    'source' => 'js:function(request, response) {
                        var branchId = $("#' . CHtml::activeId($sale->header, 'branch_id') . '").val();
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: "' . CController::createUrl('/completion/ajaxJsonCustomer') . '",
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
                            $("#customer_name").html(ui.item.id);
                        }',
                    ),
                    'htmlOptions' => array(
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => 'JSON',
                            'url' => CController::createUrl('AjaxJsonDownpaymentTaxTotal', array('id' => $sale->header->id)),
                            'success' => 'function(data) {
                                $("#' . CHtml::activeId($sale->header, 'sales_downpayment_id') . '").html(data.downpayment);
                                $("#tax").html(data.tax);
                                $("#grand_total").html(data.grandTotal);
                            }',
                        )),
                    ),
                )); ?>
                <?php $saleCustomer = $sale->header->customer(array('scopes' => 'resetScope')); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_name')); ?>
                    <?php echo CHtml::encode(CHtml::value($saleCustomer, 'company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
                <?php echo CHtml::error($sale->header, 'customer_id'); ?>
            </div>
            
            <div class="row">
                <?php echo CHtml::label('Customer PO#', ''); ?>
                <?php echo CHtml::activeTextField($sale->header, 'reference', array('size' => 50, 'maxlength' => 100)); ?>
                <?php echo CHtml::error($sale->header, 'reference'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($sale->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($sale->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::button('Cari Barang', array('name' => 'Search', 'onclick' => '$("#search-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#search-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('ProductId'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('sale' => $sale)); ?>
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
            <?php $this->renderPartial('//site/_search', array(
                'model' => $product,
                'specificationList' => array(),
                'listData' => $this->listData(),
                'action' => CHtml::normalizeUrl(array('create')),
            )); ?>
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
                url: "' . CController::createUrl('AjaxHtmlAddProduct', array('id' => $sale->header->id)) . '",
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