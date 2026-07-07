<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("'.$startDate.'");
	$("#EndDate").val("'.$endDate.'");
	$("#PageSize").val("'.$purchaseChequeSummary->dataProvider->pagination->pageSize.'");
	$("#CurrentPage").val("'.($purchaseChequeSummary->dataProvider->pagination->getCurrentPage(false) + 1).'");
	$("#CurrentSort").val("'.$currentSort.'");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/report.css');
?>

<div class="hide">
	<div class="form" style="text-align: center">

	<?php echo CHtml::beginForm(array(''), 'get'); ?>
                
		<div class="row" style="background-color: #DFDFDF">
			Bank
			<?php echo CHtml::activeDropDownlist($purchaseCheque, 'account_id', CHtml::listData(Account::model()->findAll('account_category_id IN (2)'), 'id', 'name'), array('empty'=>'-- Semua Akun --')); ?>
		</div>
			
		<div class="row">
			Jumlah per Halaman
			<?php echo CHtml::textField('PageSize', '', array('size'=>3)); ?>

			Halaman saat ini
			<?php echo CHtml::textField('page', '', array('size'=>3, 'id'=>'CurrentPage')); ?>
		</div>
			
		<div class="row">
			Tanggal Mulai
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'StartDate',
				'options'=>array(
						'dateFormat'=>'yy-mm-dd',
				),
				'htmlOptions'=>array(
						'readonly'=>true,
				),
			)); ?>

			Sampai
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'EndDate',
				'options'=>array(
						'dateFormat'=>'yy-mm-dd',
				),
				'htmlOptions'=>array(
						'readonly'=>true,
				),
			)); ?>
		</div>
                
		<div class="row">
			<?php echo CHtml::hiddenField('sort', '', array('id'=>'CurrentSort')); ?>
		</div>

		<div class="row">
			Cabang
			<?php echo CHtml::dropDownList('branch', 'id', $listData, array(
				'empty' => '-Pilih Cabang-', 
				'options' => array($branch => array('selected' => TRUE)))); //to preserve the selected value
			?>				
		</div>

		<div class="row button">
			<?php echo CHtml::submitButton('Show', array('onclick'=>'$("#CurrentSort").val(""); return true;')); ?>
			<?php echo CHtml::resetButton('Clear'); ?>
		</div>
                
	<?php echo CHtml::endForm(); ?>

	</div>

	<hr />

	<div class="right"><?php echo ReportHelper::summaryText($purchaseChequeSummary->dataProvider); ?></div>
	<div class="clear"></div>
	<div class="right">
		<?php echo ReportHelper::sortText($purchaseChequeSummary->dataProvider->sort, array('Tanggal', 'Supplier')); ?>
	</div>
	<div class="clear"></div>
</div>

<div>
	<?php $this->renderPartial('_summary', array('purchaseChequeSummary'=>$purchaseChequeSummary, 'branch'=>$branch, 'startDate'=>$startDate, 'endDate'=>$endDate)); ?>
</div>

<div class="hide">
	<div class="right">
		<?php $this->widget('system.web.widgets.pagers.CLinkPager', array(
			'itemCount'=>$purchaseChequeSummary->dataProvider->pagination->itemCount,
			'pageSize'=>$purchaseChequeSummary->dataProvider->pagination->pageSize,
			'currentPage'=>$purchaseChequeSummary->dataProvider->pagination->getCurrentPage(false),
		)); ?>
	</div>
	<div class="clear"></div>
</div>