<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 50% }
	.width1-3 { width: 30% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger">
		<?php echo (!empty($branchId)) 
			? CHtml::encode(CHtml::value(Branch::model()->findByPk($branchId), 'name'))
			: ''; ?></div>
	<div style="font-size: larger">Laporan Piutang Penjualan</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Kode</th>
		<th class="width1-2">Nama Akun</th>
		<th class="width1-3">Total Piutang</th>
	</tr>
	<tr id="header2">
		<td colspan="3"></td>
	</tr>
	<?php foreach ($customerReceivableSummary->dataProvider->data as $header): ?>
		<?php if ($header->getEndBalanceLedger($header->id, $endDate)): ?>
			<tr class="items1">
				<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'code')); ?></td>
				<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
				<td class="width1-3" style="text-align: right">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', 
					$header->getEndBalanceLedger($header->id, $endDate))); ?></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	<tr>
			<td style="border-top: 1px solid; text-align: right; font-weight: bold; font-size: small" colspan="2">
				TOTAL
			</td>
		
			<td style="border-top: 1px solid; font-weight: bold; text-align: right">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00',
					ceil($customerReceivableSummary->reportGrandTotal($endDate)))); ?>
			</td>
	</tr>
</table>