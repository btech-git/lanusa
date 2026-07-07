<?php
$this->breadcrumbs = array(
	'Fee Invoices'=>array('index'),
	'Manage',
);

$this->menu = array(
	array('label'=>'List FeeInvoice', 'url'=>array('index')),
	array('label'=>'Create FeeInvoice', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('fee-invoice-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Invoices</h1>

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
    <br/>
    <div class="row">
        <?php echo CHtml::submitButton('Show', array('onclick' => '$("#CurrentSort").val(""); return true;', 'name' => 'Submit')); ?>
        <?php echo CHtml::resetButton('Clear'); ?>
    </div>
    <?php echo CHtml::endForm(); ?>

    <br/>
    <?php
    $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
    $pageSizeDropDown = CHtml::dropDownList('pageSize', $pageSize, array(10 => 10, 25 => 25, 50 => 50, 100 => 100), array(
        'class' => 'change-pagesize',
        'onchange' => "$.fn.yiiGridView.update('fee-invoice-grid',{data:{pageSize:$(this).val()}});",
    )); ?>

    <div class="page-size-wrap">
        <span>Display by:</span><?php echo $pageSizeDropDown; ?>
    </div>	

</center>              

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'fee-invoice-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
            array(
                'name' => 'cn_ordinal',
                'header' => 'Transaction #',
                'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($model, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
                '<div style="display: inline-block"> &nbsp; // &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeDropDownList($model, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
                '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
                '<div style="display: inline-block">' . CHtml::activeTextField($model, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
                'value' => '$data->getCodeNumber(FeeInvoice::CN_CONSTANT)',
                'htmlOptions' => array('style' => 'width: 200px'),
            ),
            array(
                'header' => 'Tanggal',
                'name' => 'date',
                'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
            ),
            array(
                'name' => 'branch_id',
                'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.code')), 'id', 'code'),
                'value' => '$data->branch->code',
            ),
            array(
                'name' => 'customer_id',
                'filter' => CHtml::listData(Customer::model()->findAll(array('order' => 't.company')), 'id', 'company'),
                'value' => '$data->customer->company',
            ),
            'note',
            array(
                'name' => 'is_inactive',
                'filter' => array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive'),
                'value' => '$data->Status',
            ),
            array(
                'class'=>'CButtonColumn',
            ),
	),
)); ?>
