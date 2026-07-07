<h1>Kelola Data Penerimaan Giro</h1>
<div id="link">
    <?php echo CHtml::link('Create', array('create')); ?>
</div>
<center>
    <?php echo CHtml::beginForm(array(''), 'get'); ?>
    <div class="row">
        Tanggal Mulai
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'StartDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'readonly' => true,
            ),
        ));
        ?>

        Sampai
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'EndDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'readonly' => true,
            ),
        ));
        ?>
    </div>
    <div class="row">
        <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
    </div>
    <br/>
    <div class="row">
        <?php
        echo CHtml::submitButton('Show', array(
            'onclick' => '$("#CurrentSort").val(""); return true;',
            'name' => 'Submit'));
        ?>
        <?php echo CHtml::resetButton('Clear'); ?>
    </div>
    <?php echo CHtml::endForm(); ?>

    <br/>
    <?php
    $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
    $pageSizeDropDown = CHtml::dropDownList(
                    'pageSize', $pageSize, array(10 => 10, 25 => 25, 50 => 50, 100 => 100), array(
                'class' => 'change-pagesize',
                'onchange' => "$.fn.yiiGridView.update('cheque-grid',{data:{pageSize:$(this).val()}});",
                    )
    );
    ?>

    <div class="page-size-wrap">
        <span>Display by:</span><?php echo $pageSizeDropDown; ?>
    </div>	
</center>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'cheque-grid',
    'dataProvider' => $dataProvider,
    'filter' => $saleCheque,
    'columns' => array(
        array(
            'name' => 'cn_ordinal',
            'header' => 'Penerimaan Giro #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($saleCheque, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
            '<div style="display: inline-block"> &nbsp; /' . SaleCheque::CN_CONSTANT . '/ &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeDropDownList($saleCheque, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeTextField($saleCheque, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(SaleCheque::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'receive_date',
            'filter' => FALSE,
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->receive_date)'
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'due_date',
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->due_date)'
        ),
        array(
            'header' => 'Customer',
            'filter' => CHtml::dropDownList('CustomerId', $customerId, CHtml::listData(Customer::model()->findAll(array('order' => 't.name')), 'id', 'company'), array('empty' => '')),
            'value' => 'isset ($data->saleChequeDetails[0])
				? $data->saleChequeDetails[0]->saleReceiptHeader->customer->company
				: ""',
        ),
        array(
            'name' => 'branch_id',
            'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
            'value' => 'CHtml::encode(CHtml::value($data, "branch.name"))',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}{update}{delete}',
        ),
    ),
));
?>
