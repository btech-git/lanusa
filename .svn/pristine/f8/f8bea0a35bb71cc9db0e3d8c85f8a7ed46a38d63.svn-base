<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 10% }
	.width1-2 { width: 30% }
	.width1-3 { width: 10% }
	.width1-4 { width: 5% }
	.width1-5 { width: 5% }
	.width1-6 { width: 5% }
	.width1-7 { width: 5% }
	.width1-8 { width: 15% }
	.width1-9 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger">Laporan Stok Barang Global</div>
	<div>
		<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?>
	</div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Kategori</th>
		<th class="width1-2">Nama Produk</th>
		<th class="width1-3">Ukuran</th>
		<th class="width1-4">Stok Begin</th>
		<th class="width1-5">Stok In</th>
		<th class="width1-6">Stok Out</th>
		<th class="width1-7">Stok End</th>
		<th class="width1-8">Hrg Average</th>
		<th class="width1-9">Total</th>
	</tr>
	<tr id="header2">
			<td colspan="9"></td>
	</tr>
	<?php foreach ($stockSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'category.name')); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
			<td class="width1-3" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, 'size')); ?></td>
			<td class="width1-4" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->getStockBeginning($startDate))); ?></td>
			<td class="width1-5" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->getStockIn($startDate, $endDate))); ?></td>
			<td class="width1-6" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->getStockOut($startDate, $endDate))); ?></td>
			<td class="width1-7" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->getStockEnding($endDate))); ?></td>
			<td class="width1-8" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->getGlobalStockItemPrice())); ?></td>
			<td class="width1-9" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->getGlobalStockPrice())); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="9"></td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="6" style="font-weight: bold">TOTAL</td>
		<td class="width1-7" style="border-top: 1px solid; font-weight: bold; text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $this->reportTotalStock($stockSummary->dataProvider))); ?></td>
		<td class="width1-8" style="border-top: 1px solid; font-weight: bold; text-align: center"></td>
		<td class="width1-9" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportTotalStockPrice($stockSummary->dataProvider))); ?></td>
	</tr>
</table>