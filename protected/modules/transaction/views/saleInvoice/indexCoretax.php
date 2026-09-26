<h1>Kelola Data Manual Sale Invoice</h1>

<center>
    <?php echo CHtml::beginForm(array(''), 'get'); ?>
    <div class="row">
        Tanggal Mulai
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'StartDate',
            'value' => $startDate,
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'readonly' => true,
            ),
        )); ?>

        Sampai
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'EndDate',
            'value' => $endDate,
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'readonly' => true,
            ),
        )); ?>
    </div>
    
    <div class="row">
        <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
    </div>

    <br/>
    
    <div class="row button">
        <?php echo CHtml::submitButton('Show', array('onclick' => '$("#CurrentSort").val(""); return true;', 'name' => 'Submit')); ?>
        <?php echo CHtml::resetButton('Clear'); ?>
    </div>
    
    <?php echo CHtml::endForm(); ?>
</center>

<?php echo CHtml::beginForm(array(''), 'get'); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sale-invoice-grid',
    'dataProvider' => $dataProvider,
    'filter' => $saleInvoice,
    'columns' => array(
        array(
            'id' => 'selectedIds',
            'class' => 'CCheckBoxColumn',
            'selectableRows' => '50',
        ),
        array(
            'name' => 'cn_ordinal',
            'header' => 'Invoice #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleInvoice, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
            '<div style="display: inline-block"> &nbsp; /' . SaleInvoice::CN_CONSTANT . '/ &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeDropDownList($saleInvoice, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeTextField($saleInvoice, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(SaleInvoice::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 300px'),
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'date',
            'filter' => FALSE,
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
        ),
        array(
            'header' => 'Customer',
            'filter' => CHtml::textField('CustomerCompany', $customerCompany),
            'value' => 'CHtml::encode(CHtml::value($data, "deliveryHeader.saleHeader.customer.company"))',
        ),
        'reference',
        array(
            'header' => 'Branch',
            'filter' => CHtml::activeDropDownList($saleInvoice, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'code'), array('empty' => '')),
            'value' => 'CHtml::encode(CHtml::value($data, "branch.code"))',
        ),
        array(
            'header' => 'Status',
            'name' => 'is_inactive',
            'filter' => false,
            'value' => '$data->Status',
        ),
    ),
)); ?>

<?php echo CHtml::submitButton('Export E-Faktur (XML)', array('name' => 'SaveXml', 'style' => 'float: left;', 'class' => 'grey-btn')); ?>
<?php echo CHtml::endForm(); ?>
