<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
	$("#PageSize").val("' . $generalLedgerSummary->dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($generalLedgerSummary->dataProvider->pagination->getCurrentPage(false) + 1) . '");
	$("#CurrentSort").val("' . $currentSort . '");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/report.css');
?>

<div class="hide">
    <div class="form" style="text-align: center">

        <?php echo CHtml::beginForm(array(''), 'get'); ?>


        <div class="row" style="background-color: #DFDFDF">
            Cabang
            <?php echo CHtml::hiddenField('BranchId'); ?>
            <?php
            echo CHtml::activeDropDownlist($branch, 'id', CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array(
                'empty' => '-- Pilih Cabang --',
                'onchange' => '
						$("#BranchId").val(this.value);
						$.ajax({
							type: "POST",
							url: "' . CController::createUrl('ajaxHtmlAccount') . '",
							data: $("#BranchId").serialize(),
							success: function(html)
							{
								$("#account_div").html(html);
							}
						});
					'
            ));
            ?>
        </div>   

        <!--			<div class="row" style="background-color: #DFDFDF">
                                        Cabang
<?php // echo CHtml::dropDownlist('BranchId', $branchId, CHtml::listData(Branch::model()->findAll(), 'id', 'name'), array('empty'=>'-- Semua Cabang --'));  ?>
                                </div>-->

        <div class="row">
            Jumlah per Halaman
            <?php echo CHtml::textField('PageSize', '', array('size' => 3)); ?>

            Halaman saat ini
<?php echo CHtml::textField('page', '', array('size' => 3, 'id' => 'CurrentPage')); ?>
        </div>

        <div class="row">
            Tanggal Mulai
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'StartDate',
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth'=>true,
                    'changeYear'=>true,
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
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                ),
            ));
            ?>
        </div>

<?php
$this->renderPartial('_account', array(
    'accounts' => $accounts,
    'startAccount' => $startAccount,
    'endAccount' => $endAccount,
));
?>

        <!--			<div class="row" style="background-color: #DFDFDF">
                                        Akun
<?php // echo CHtml::dropDownlist('StartAccount', $startAccount, CHtml::listData(Account::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array('empty'=>'-- Akun Awal --'));  ?>
            <?php // echo CHtml::dropDownlist('EndAccount', $endAccount, CHtml::listData(Account::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array('empty'=>'-- Akun Akhir --'));  ?>
                                
                                </div>-->

        <div class="row">
            <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
        </div>

        <div class="row button">
        <?php echo CHtml::submitButton('Show', array('onclick' => '$("#CurrentSort").val(""); return true;')); ?>
<?php echo CHtml::resetButton('Clear'); ?>
        </div>

        <div class="row button">
<?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
        </div>

        <?php echo CHtml::endForm(); ?>

    </div>

    <hr />

    <div class="right"><?php echo ReportHelper::summaryText($generalLedgerSummary->dataProvider); ?></div>
    <div class="clear"></div>
    <div class="right">
<?php //echo ReportHelper::sortText($generalLedgerSummary->details->sort, array('Tanggal'));  ?>
<?php //echo ReportHelper::sortText($generalLedgerSummary->dataProvider->sort, array('Code'));  ?>
    </div>
    <div class="clear"></div>
</div>

<div>
        <?php $this->renderPartial('_summary', array('generalLedgerSummary' => $generalLedgerSummary, 'startDate' => $startDate, 'endDate' => $endDate, 'branchName' => $branchName)); ?>
</div>

<div class="hide">
    <div class="right">
<?php
$this->widget('system.web.widgets.pagers.CLinkPager', array(
    'itemCount' => $generalLedgerSummary->dataProvider->pagination->itemCount,
    'pageSize' => $generalLedgerSummary->dataProvider->pagination->pageSize,
    'currentPage' => $generalLedgerSummary->dataProvider->pagination->getCurrentPage(false),
));
?>
    </div>
    <div class="clear"></div>
</div>