<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($saleCheque->header); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Penerimaan Giro #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($saleCheque->header->getCodeNumber(SaleCheque::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal Terima', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleCheque->header,
                    'attribute' => 'receive_date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'readonly' => true,
                    ),
                ));
                ?>
                <?php echo CHtml::error($saleCheque->header, 'receive_date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Jatuh Tempo', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $saleCheque->header,
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
                <?php echo CHtml::error($saleCheque->header, 'due_date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($saleCheque->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($saleCheque->header, 'note'); ?>
            </div>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::activeLabelEx($saleCheque->header, 'branch_id'); ?>
                <?php if ($saleCheque->header->isNewRecord): ?>
                    <?php
                    echo CHtml::activeDropDownList($saleCheque->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                        'onchange' => CHtml::ajax(array(
                            'type' => 'POST',
                            'dataType' => "JSON",
                            'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $saleCheque->header->id)),
                            'success' => 'function(data) {
                                $("#code_number").html(data.codeNumber);
                            }',
                        )) . '
                        $.fn.yiiGridView.update("customer-grid", {
                            data: $("form").serialize()
                        });

                        $.fn.yiiGridView.update("receipt-header-grid", {
                            data: $("form").serialize()
                        });',
                    ));
                    ?>
                    <?php echo CHtml::error($saleCheque->header, 'branch_id'); ?>
                <?php else: ?>
                    <?php echo CHtml::encode(CHtml::value($saleCheque->header, 'branch.name')); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Customer', ''); ?>
                <?php if ($saleCheque->header->isNewRecord): ?>
                    <?php echo CHtml::activeTextField($saleCheque->header, 'customer_id', array('readonly' => true, 'onclick' => '$("#customer-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#customer-dialog").dialog("open"); return false; }')); ?>

                    <span id="customer_id">
                    <?php echo CHtml::encode(CHtml::value($saleCheque->header, 'saleChequeDetails[0].saleReceiptHeader.customer.company')); ?>
                    </span>

                    <?php echo CHtml::error($saleCheque->header, 'customer_id'); ?>

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
                            $("#' . CHtml::activeId($saleCheque->header, 'customer_id') . '").val($.fn.yiiGridView.getSelection(id));
                            $("#customer-dialog").dialog("close");
                            if ($.fn.yiiGridView.getSelection(id) == "") {
                                $("#customer_id").html("");
                                $("#customer_name").html("");
                                $("#customer_address").html("");

                            } else {
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "' . CController::createUrl('ajaxJsonCustomer', array('id' => $saleCheque->header->id)) . '",
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
                                url: "' . CController::createUrl('ajaxHtmlResetDetail', array('id' => $saleCheque->header->id)) . '",
                                data: $("form").serialize(),
                                success: function(html) { $("#detail_div").html(html); },
                            });

                            //update sale invoice with customer filter
                            $.fn.yiiGridView.update("sale-receipt-header-grid", {
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
                <?php else: ?>
                    <?php echo isset($saleCheque->header->saleChequeDetails[0]) ? CHtml::encode($saleCheque->header->saleChequeDetails[0]->saleReceiptHeader->customer->company) : ''; ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Nama Pelanggan', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_name')); ?>
                <?php if (!$saleCheque->header->isNewRecord): ?>
                    <?php echo isset($saleCheque->header->saleChequeDetails[0]) ? CHtml::encode($saleCheque->header->saleChequeDetails[0]->saleReceiptHeader->customer->name) : ''; ?>
                <?php endif; ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('Alamat Pelanggan', ''); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_address')); ?>
                <?php if (!$saleCheque->header->isNewRecord): ?>
                    <?php echo isset($saleCheque->header->saleChequeDetails[0]) ? CHtml::encode($saleCheque->header->saleChequeDetails[0]->saleReceiptHeader->customer->address) : ''; ?>
                <?php endif; ?>
                <?php echo CHtml::closeTag('span'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php
        echo CHtml::button('Tambah Tanda Terima', array(
            'name' => 'Search',
            'onclick' => '$("#sale-receipt-header-dialog").dialog("open"); return false;',
            'onkeypress' => 'if (event.keyCode == 13) { $("#sale-receipt-header-dialog").dialog("open"); return false; }'));
        ?>
        <?php echo CHtml::hiddenField('SaleReceiptHeaderId'); ?>
    </div>


    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('saleCheque' => $saleCheque)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
    </div>
    <?php echo IdempotentManager::generate(); ?>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'sale-receipt-header-dialog',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Sale Receipt Header',
        'autoOpen' => false,
        'width' => 'auto',
        'modal' => true,
    ),
));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sale-receipt-header-grid',
    'dataProvider' => $saleReceiptHeaderDataProvider,
    'filter' => $saleReceiptHeader,
    'selectionChanged' => 'js:function(id) {
			$("#SaleReceiptHeaderId").val($.fn.yiiGridView.getSelection(id));
			$("#sale-receipt-header-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('AjaxHtmlAddSaleReceipt', array('id' => $saleCheque->header->id)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
    'columns' => array(
        array(
            'name' => 'cn_ordinal',
            'header' => 'Tanda Terima #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleReceiptHeader, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
            '<div style="display: inline-block"> &nbsp; /' . SaleReceiptHeader::CN_CONSTANT . '/ &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeDropDownList($saleReceiptHeader, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeTextField($saleReceiptHeader, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'date',
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
        ),
        array(
            'header' => 'Jatuh Tempo',
            'name' => 'due_date',
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->due_date)'
        ),
        array(
            'name' => 'customer_id',
            'header' => 'Pelanggan',
            'filter' => false, //CHtml::listData(Customer::model()->findAll(array('order' => 'name ASC')), 'id', 'company'),
            'value' => 'CHtml::value($data, "customer.company")',
        ),
        array(
            'header' => 'Total',
            'filter' => false,
            'value' => 'Yii::app()->numberFormatter->format("#,##0.00", $data->totalInvoice)',
            'htmlOptions' => array(
                'style' => 'text-align: right',
            ),
        ),
    ),
));
?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>