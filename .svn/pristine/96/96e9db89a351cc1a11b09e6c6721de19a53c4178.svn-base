<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("'.$startDate.'");
	$("#EndDate").val("'.$endDate.'");
	$("#PageSize").val("'.$customerReceivableSummary->dataProvider->pagination->pageSize.'");
	$("#CurrentPage").val("'.($customerReceivableSummary->dataProvider->pagination->getCurrentPage(false) + 1).'");
	$("#CurrentSort").val("'.$currentSort.'");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/report.css');
?>

<div class="hide">
	<div class="form" style="text-align: center">

		<?php echo CHtml::beginForm(array(''), 'get'); ?>
                
			<div class="row" style="background-color: #DFDFDF">
				Cabang
				<?php echo CHtml::dropDownlist('BranchId', $branchId, 
					CHtml::listData(Branch::model()->findAll(), 'id', 'name'),
					array(
						'empty'=>'-- Pilih Cabang --',
						'onchange' => '$.ajax({
							type: "POST",
							url: "'.CController::createUrl('ajaxHtmlAccount').'",
							data: $("#BranchId, #StartAccount, #EndAccount").serialize(),
							success: function(html){
								$("#account_span").html(html);
							}
						})'
					)); ?>
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
		
			<div class="row" style="background-color: #DFDFDF">
				Akun
				<span id="account_span">
					<?php $this->renderPartial('_account', array(
						'accounts' => $accounts,
						'startAccount' => $startAccount,
						'endAccount' => $endAccount
					)); ?>
				</span>
			</div>
                
			<div class="row">
				<?php echo CHtml::hiddenField('sort', '', array('id'=>'CurrentSort')); ?>
			</div>

			<div class="row button">
				<?php echo CHtml::submitButton('Show', array('onclick'=>'$("#CurrentSort").val(""); return true;')); ?>
				<?php echo CHtml::resetButton('Clear'); ?>
			</div>
            
                        <div class="row button">
                                <?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
                        </div>
                
        <?php echo CHtml::endForm(); ?>

	</div>

	<hr />

</div>

<div>
	<?php $this->renderPartial('_summary', array(
		'customerReceivableSummary'=>$customerReceivableSummary,
		'startDate'=>$startDate,
		'endDate'=>$endDate,
		'branchId' => $branchId
		)); ?>
</div>

<div class="hide">
	<div class="right">
		<?php $this->widget('system.web.widgets.pagers.CLinkPager', array(
				'itemCount'=>$customerReceivableSummary->dataProvider->pagination->itemCount,
				'pageSize'=>$customerReceivableSummary->dataProvider->pagination->pageSize,
				'currentPage'=>$customerReceivableSummary->dataProvider->pagination->getCurrentPage(false),
		)); ?>
	</div>
	<div class="clear"></div>
</div>