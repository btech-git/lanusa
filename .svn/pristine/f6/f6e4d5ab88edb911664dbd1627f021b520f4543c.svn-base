<h1>Kelola Data Penjualan Barang</h1>

<center>
    <?php echo CHtml::beginForm(array(''), 'get'); ?>
    <div class="row">
        Tanggal Mulai
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'StartDate',
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
    
    <div class="row">
        <?php echo CHtml::submitButton('Show', array(
            'onclick' => '$("#CurrentSort").val(""); return true;',
            'name' => 'Submit'
        )); ?>
        <?php echo CHtml::resetButton('Clear'); ?>
    </div>
    <?php echo CHtml::endForm(); ?>

    <br/>
    
    <?php
    $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
    $pageSizeDropDown = CHtml::dropDownList(
        'pageSize', $pageSize, array(10 => 10, 25 => 25, 50 => 50, 100 => 100), array(
            'class' => 'change-pagesize',
            'onchange' => "$.fn.yiiGridView.update('delivery-grid',{data:{pageSize:$(this).val()}});",
        )
    );
    ?>

    <div class="page-size-wrap">
        <span>Display by:</span><?php echo $pageSizeDropDown; ?>
    </div>	
</center>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'delivery-grid',
    'dataProvider' => $dataProvider,
    'filter' => $sale,
    'columns' => array(
        array(
            'name' => 'cn_ordinal',
            'header' => 'Pengiriman #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($sale, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
            '<div style="display: inline-block"> &nbsp; /' . SaleHeader::CN_CONSTANT . '/ &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeDropDownList($sale, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeTextField($sale, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(SaleHeader::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 250px'),
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'date',
            'filter' => FALSE,
            'value' => 'Yii::app()->dateFormatter->format("d MMM yyyy", $data->date)',
            'htmlOptions' => array('style' => 'width: 100px'),
        ),
        array(
            'header' => 'Customer',
            'filter' => CHtml::activeTextField($sale, 'customerCompany'),
            'value' => '$data->customer->company',
        ),
        array(
            'name' => 'branch_id',
            'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
            'value' => '$data->branch->name',
        ),
        'reference',
        array(
            'name' => 'is_inactive',
            'filter' => array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive'),
            'value' => '$data->status',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'label' => 'view',
                    'url' => 'Yii::app()->createUrl("transaction/sale/viewWarehouse", array("id"=>$data->id))',
                ),
            ),
        ),
    ),
)); ?>
