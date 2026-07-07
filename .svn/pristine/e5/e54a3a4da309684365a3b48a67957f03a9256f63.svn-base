<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 15% }
	.width1-4 { width: 25% }
	.width1-5 { width: 15% }
	.width1-6 { width: 10% }
	.width1-7 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Pembelian Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Pembelian #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-4">Supplier</th>
		<th class="width1-5">DPP</th>
		<th class="width1-6">PPN</th>
		<th class="width1-7">Total</th>
	</tr>
	<tr id ="header2">
		<td colspan="7"></td>
	</tr>
	<?php foreach ($purchaseRecapSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1" style="text-align: center"><?php echo CHtml::encode($header->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-4" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, isset($header->supplier->company) ? 'supplier.company' : 'supplier.name')); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->totalBeforeTax))); ?></td>
			<td class="width1-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->calculatedTax))); ?></td>
			<td class="width1-7" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->grandTotal))); ?></td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="5" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PEMBELIAN</td>
		<td class="width1-4" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportGrandTotal($purchaseRecapSummary->dataProvider))); ?></td>
	</tr>
</table>
