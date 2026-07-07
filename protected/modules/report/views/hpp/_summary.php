<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 40% }
	.width1-3 { width: 10% }
	.width1-4 { width: 15% }
	.width1-5 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger">Laporan Harga Pokok Penjualan</div>
</div>

<br />

<table class="report">
	<tr id="header1">
			<th class="width1-1">Kategori</th>
			<th class="width1-2">Nama Produk</th>
			<th class="width1-3">Ukuran</th>
			<th class="width1-4">Stok</th>
			<th class="width1-5">HPP (Rp.)</th>
	</tr>
	<tr id="header2">
		<td colspan="5"></td>
	</tr>
	<?php foreach ($hppSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'category.name')); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
			<td class="width1-3" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, 'size')); ?></td>
			<td class="width1-4" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->getGlobalStock()))); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($header, 'costOfGoodsSold'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="5"></td>
		</tr>
	<?php endforeach; ?>
		<tr>
				<td class="width1-1" style="border-top: 1px solid"></td>
				<td class="width1-2" style="border-top: 1px solid"></td>
				<td class="width1-3" style="border-top: 1px solid"></td>
				<td class="width1-4" style="border-top: 1px solid; text-align: right; font-weight: bold; font-size: small">TOTAL HPP</td>
				<td class="width1-5" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($this->reportGrandTotal($hppSummary->dataProvider)))); ?></td>
			</tr>
</table>