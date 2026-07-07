<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 20% }
	.width1-3 { width: 20% }
	.width1-4 { width: 20% }
	.width1-5 { width: 20% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penjualan Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Penjualan #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Reference</th>
		<th class="width1-4">Customer</th>
		<th class="width1-5">Total</th>
	</tr>
	<tr id ="header2">
		<td colspan="7"></td>
	</tr>
	<?php foreach ($saleRecapSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1" style="text-align: center"><?php echo CHtml::encode($header->getCodeNumber(SaleHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-3" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, 'reference')); ?></td>
			<td class="width1-4" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, isset($header->customer->company) ? 'customer.company' : 'customer.name')); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->grandTotal))); ?></td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="4" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PENJUALAN</td>
		<td class="width1-5" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportGrandTotal($saleRecapSummary->dataProvider))); ?></td>
	</tr>
</table>
