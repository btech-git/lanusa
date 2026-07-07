<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#AccountId").val("' . $accountId . '");
	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
	$("#PageSize").val("' . $dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($dataProvider->pagination->getCurrentPage(false) + 1) . '");
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

        <?php
        $this->renderPartial('_account', array(
            'accounts' => $accounts,
            'accountId' => $accountId
        ));
        ?>

        <div class="row">
            Jumlah per Halaman
<?php echo CHtml::textField('PageSize', '', array('size' => 3)); ?>

            Halaman saat ini
            <?php echo CHtml::textField('CurrentPage', '', array('size' => 3)); ?>
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

        <div class="row button">
        <?php echo CHtml::submitButton('Show'); ?>
<?php echo CHtml::resetButton('Clear'); ?>
        </div>

        <div class="row button">
<?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
        </div>

<?php echo CHtml::endForm(); ?>
    </div>

    <hr />

    <div class="right"><?php echo ReportHelper::summaryText($dataProvider); ?></div>
    <div class="clear"></div>
</div>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branchName, 'name')); ?></div>
    <div style="font-size: larger">Laporan <?php echo CHtml::encode(CHtml::value($accountName, 'name')); ?></div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table style="width: 90%; margin: 0 auto; border-spacing: 0pt">
    <tr>
        <th style="width: 15%; text-align: center; font-size: larger; border-bottom: 1px solid">Tanggal</th>
        <th style="width: 15%; text-align: center; font-size: larger; border-bottom: 1px solid">No. Perkiraan</th>
        <th style="width: 40%; text-align: center; font-size: larger; border-bottom: 1px solid">Keterangan</th>
        <th style="width: 15%; text-align: center; font-size: larger; border-bottom: 1px solid">Debit</th>
        <th style="width: 15%; text-align: center; font-size: larger; border-bottom: 1px solid">Credit</th>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: right" colspan="3">SALDO AWAL</td>
        <td style="font-weight: bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $account->getBeginningBalance($accountId, $startDate, $branchId))); ?></td>
        <td></td>
    </tr>
    <?php foreach ($dataProvider->data as $data): ?>
        <?php $accountDetail = Account::model()->findByPk($data['detail_account_id']); ?>
        <tr>
            <td><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($data['date']))); ?></td>
            <td><?php echo CHtml::encode(CHtml::value($data, 'module')); ?></td>
            <td><?php echo CHtml::encode(CHtml::value($data, 'note')); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $data['debit'])); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $data['credit'])); ?></td>
        </tr>
<?php endforeach; ?>
    <tr>
        <td style="font-weight: bold; text-align: right" colspan="3">SALDO AKHIR</td>
        <td style="font-weight: bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $account->getEndingBalance($accountId, $endDate, $branchId))); ?></td>
        <td></td>
    </tr>
</table>

<br />

<div class="hide">
    <div class="right">
<?php
$this->widget('system.web.widgets.pagers.CLinkPager', array(
    'pages' => $dataProvider->pagination,
    'itemCount' => $dataProvider->pagination->itemCount,
    'pageSize' => $dataProvider->pagination->pageSize,
    'currentPage' => $dataProvider->pagination->getCurrentPage(false),
));
?>
    </div>
    <div class="clear"></div>
</div>