<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="container">
        <div class="span-12">
            <div class="row">
                <?php echo CHtml::label('Inden #', false); ?>
                <span id="code_number">
                    <?php echo CHtml::encode($indent->header->getCodeNumber(IndentHeader::CN_CONSTANT)); ?>
                </span>	
            </div>

            <div class="row">
                <?php echo CHtml::label('Tanggal', false); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $indent->header,
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
                <?php echo CHtml::error($indent->header, 'date'); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabelEx($indent->header, 'customer_id'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'model' => $indent->header,
                    'attribute' => 'customer_id',
                    'sourceUrl' => CController::createUrl('/completion/customer'),
                    //additional javascript options for the autocomplete plugin
                    'options' => array(
                        'minLength' => '2',
                        'select' => 'js:function(event, ui) {
                            $("#customer_name").html(ui.item.id);
                        }',
                    ),
                ));
                ?>
                <?php $indentCustomer = $indent->header->customer(array('scopes' => 'resetScope')); ?>
                <?php echo CHtml::openTag('span', array('id' => 'customer_name')); ?>
                <?php echo CHtml::encode(CHtml::value($indentCustomer, 'company')); ?>
                <?php echo CHtml::closeTag('span'); ?>
                <?php echo CHtml::error($indent->header, 'customer_id'); ?>
            </div>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($indent->header, 'branch_id'); ?>
            <?php if ($indent->header->isNewRecord): ?>
                <?php
                echo CHtml::activeDropDownList($indent->header, 'branch_id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => "JSON",
                        'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $indent->header->id)),
                        'success' => 'function(data) {
                            $("#code_number").html(data.codeNumber);
                        }',
                    )),
                ));
                ?>
                <?php echo CHtml::error($indent->header, 'branch_id'); ?>
            <?php else: ?>
                <?php echo CHtml::encode(CHtml::value($indent->header, 'branch.name')); ?>
            <?php endif; ?>
        </div>

        <div class="span-12 last">
            <div class="row">
                <?php echo CHtml::label('Catatan', ''); ?>
                <?php echo CHtml::activeTextArea($indent->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
                <?php echo CHtml::error($indent->header, 'note'); ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <?php echo CHtml::button('Cari Barang', array('name' => 'Search', 'onclick' => '$("#search-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#search-dialog").dialog("open"); return false; }')); ?>
        <?php echo CHtml::hiddenField('ProductId'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::error($indent->header, 'error'); ?>
    </div>

    <div id="detail_div">
        <?php $this->renderPartial('_detail', array('indent' => $indent)); ?>
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
        'id' => 'search-dialog',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Products',
            'autoOpen' => false,
            'width' => 'auto',
            'modal' => true,
        ),
    ));
    ?>

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
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'product-grid',
        'dataProvider' => $dataProvider,
        'filter' => $product,
        'selectionChanged' => 'js:function(id) {
			$("#ProductId").val($.fn.yiiGridView.getSelection(id));
			$("#search-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('AjaxHtmlAddProduct', array('id' => $indent->header->id, 'nt' => $indent->header->is_non_tax)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
        'columns' => array(
            'name',
            array(
                'name' => 'unit_id',
                'filter' => CHtml::listData(Unit::model()->findAll(), 'id', 'name'),
                'value' => 'CHtml::value($data, "unit.name")',
            ),
            'size',
        ),
    ));
    ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>