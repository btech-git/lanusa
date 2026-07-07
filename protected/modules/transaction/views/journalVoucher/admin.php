<?php
$this->breadcrumbs = array(
    'Jurnal Umum' => array('create'),
    'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('indent-header-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Jurnal Umum</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<div id="link">
    <?php echo CHtml::link('Create', array('create')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'indent-header-grid',
    'dataProvider' => $dataProvider,
    'filter' => $journal,
    'columns' => array(
        array(
            'name' => 'cn_ordinal',
            'header' => 'Jurnal #',
            'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($journal, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
            '<div style="display: inline-block"> &nbsp; // &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeDropDownList($journal, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
            '<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
            '<div style="display: inline-block">' . CHtml::activeTextField($journal, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
            'value' => '$data->getCodeNumber(JournalVoucherHeader::CN_CONSTANT)',
            'htmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'header' => 'Tanggal',
            'name' => 'date',
            'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
        ),
        array(
            'name' => 'branch_id',
            'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
            'value' => '$data->branch->name',
        ),
        'note',
        array(
            'name' => 'is_inactive',
            'filter' => array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive'),
            'value' => '$data->Status',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}{update}{delete}',
        ),
    ),
));
?>