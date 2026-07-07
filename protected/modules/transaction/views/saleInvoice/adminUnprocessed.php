<h1>Manage Unprocessed Invoice</h1>

<div id="link">
    <?php echo CHtml::link('Manage Invoice', array('admin'), array('target' => '_blank')); ?>
</div>

<center>
	<?php echo CHtml::beginForm(array( '' ), 'get'); ?>
	<div class="row">
		Tanggal Mulai
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name' => 'StartDate',
            'attribute' => $startDate,
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
            'attribute' => $endDate,
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
		<?php echo CHtml::hiddenField('sort', '', array( 'id' => 'CurrentSort' )); ?>
	</div>
	<br/>
	<div class="row">
		<?php echo CHtml::submitButton('Show', array( 
			'onclick' => '$("#CurrentSort").val(""); return true;', 
			'name' => 'Submit' )); ?>
		<?php echo CHtml::resetButton('Clear'); ?>
	</div>
	<?php echo CHtml::endForm(); ?>
	
	<br/>
	<?php
		$pageSize = Yii::app()->user->getState( 'pageSize', Yii::app()->params[ 'defaultPageSize' ] );
		$pageSizeDropDown = CHtml::dropDownList(
			'pageSize', $pageSize, array( 10 => 10, 25 => 25, 50 => 50, 100 => 100 ),
			array(
				'class'    => 'change-pagesize',
				'onchange' => "$.fn.yiiGridView.update('sale-invoice-grid',{data:{pageSize:$(this).val()}});",
			)
		);
		?>

	<div class="page-size-wrap">
		<span>Display by:</span><?php echo $pageSizeDropDown; ?>
	</div>	
</center>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sale-invoice-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$saleInvoice,
	'columns'=>array(
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
		array(
			'header' => 'Branch',
			'filter' => CHtml::activeDropDownList($saleInvoice, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty'=>'')),
			'value' => 'CHtml::encode(CHtml::value($data, "branch.name"))',
		),
        'note',
		array(
			'name'=>'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE=>'Active', ActiveRecord::INACTIVE=>'Inactive'),
			'value'=>'$data->Status',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'afterDelete' => 'function(){ location.reload(); }'
		),
	),
)); ?>