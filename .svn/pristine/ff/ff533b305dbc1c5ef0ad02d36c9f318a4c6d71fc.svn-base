<h1>Kelola Data Pembelian Barang</h1>
<div id="link">
	<?php echo CHtml::link('Create', array('create')); ?>
</div>

<center>
	<?php echo CHtml::beginForm(array( '' ), 'get'); ?>
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
			'pageSize',
			$pageSize,
			array( 10 => 10, 25 => 25, 50 => 50, 100 => 100 ),
			array(
				'class'    => 'change-pagesize',
				'onchange' => "$.fn.yiiGridView.update('purchase-grid',{data:{pageSize:$(this).val()}});",
			)
		);
		?>

	<div class="page-size-wrap">
		<span>Display by:</span><?php echo $pageSizeDropDown; ?>
	</div>	
</center>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purchase-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$purchase,
	'columns'=>array(
		array(
			'name' => 'cn_ordinal',
			'header' => 'Pembelian #',
			'filter' => '<div style="display: inline-block">' . CHtml::activeTextField($purchase, 'cn_ordinal', array('maxLength' => 4, 'size' => 2)) . '</div>' .
						'<div style="display: inline-block"> &nbsp; /' . PurchaseHeader::CN_CONSTANT . '/ &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeDropDownList($purchase, 'cn_month', array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'), array('empty' => '')) . '</div>' .
						'<div style="display: inline-block"> &nbsp; / &nbsp; </div>' .
						'<div style="display: inline-block">' . CHtml::activeTextField($purchase, 'cn_year', array('maxLength' => 2, 'size' => 2)) . '</div>',
			'value' => '$data->getCodeNumber(PurchaseHeader::CN_CONSTANT)',
			'htmlOptions' => array('style' => 'width: 200px'),
		),
		array(
			'header' => 'Tanggal',
			'name' => 'date',
                        'filter' => FALSE,
			'value' => 'Yii::app()->dateFormatter->format("d MMMM yyyy", $data->date)'
		),
		array(
			'name' => 'supplier_id',
			'filter' => CHtml::listData(Supplier::model()->findAll(array('order' => 't.name')), 'id', 'company'),
			'value' => 'CHtml::encode(CHtml::value($data, "supplier.company"))',
		),
		array(
			'name' => 'branch_id',
			'filter' => CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'),
			'value' => 'CHtml::encode(CHtml::value($data, "branch.name"))',
		),
		array(
			'name'=>'is_inactive',
			'filter' => array(ActiveRecord::ACTIVE=>'Active', ActiveRecord::INACTIVE=>'Inactive'),
			'value'=>'$data->status',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}',
		),
	),
)); ?>
