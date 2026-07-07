<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 40% }
	.width1-3 { width: 10% }
	.width1-4 { width: 15% }
	.width1-4 { width: 15% }

	.width2-1 { width: 10% }
	.width2-2 { width: 10% }
	.width2-3 { width: 15% }
	.width2-4 { width: 8% }
	.width2-5 { width: 8% }
	.width2-6 { width: 8% }
	.width2-7 { width: 10% }
	.width2-8 { width: 10% }
	.width2-9 { width: 21% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger">Laporan Gudang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Kategori</th>
		<th class="width1-2">Nama Produk</th>
		<th class="width1-3">Ukuran</th>
		<th class="width1-4">Stok Awal</th>
		<th class="width1-5">Stok Akhir</th>
	</tr>
	<tr id="header2">
		<td colspan="5">
			<table>
				<tr>
					<th class="width2-1">Transaksi #</th>
					<th class="width2-2">Tanggal</th>
					<th class="width2-3">Ket</th>
					<th class="width2-4">Qty In</th>
					<th class="width2-5">Qty Out</th>
					<th class="width2-6">Qty End</th>
					<th class="width2-7">Avg Price</th>
					<th class="width2-8">Total</th>
					<th class="width2-9">Gudang</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($inventorySummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'category.name')); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
			<td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'size')); ?></td>
			<td>
				<?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->getStockBeginning($startDate)))); ?>
				<?php echo $header->getInventoryReportStockFromBeginningTo($startDate, $warehouseId); ?>
			</td>
			<td class="width1-4" style="text-align: center">
				<?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->getGlobalStock()))); ?>
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->getInventoryReportStockFromBeginningTo($endDate, $warehouseId, true))); ?>
			</td>
		</tr>
		<tr class="items2">
			<td colspan="5">
				<table>
				<?php $reportCurrentStock = $header->getInventoryReportStockFromBeginningTo($startDate, $warehouseId); ?>
					<?php foreach ($header->inventories as $detail): ?>
						<?php //if ($detail->date >= $startDate && $detail->date <= $endDate): ?>
							<tr>
								<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'transaction_ordinal')); ?></td>
								<td class="width2-2" style="text-align: right"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($detail->date))); ?></td>
								<td class="width2-3"><?php echo CHtml::encode(CHtml::value($detail, 'transaction_subject')); ?></td>
								<td class="width2-4" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity_in'))); ?></td>
								<td class="width2-5" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity_out'))); ?></td>
								<td class="width2-7" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($currentStock = $detail->getEndingQuantity($reportCurrentStock)))); ?></td>
								<td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'price'))); ?></td>
								<td class="width2-8" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $detail->getEndingPrice($currentStock))); ?></td>
								<td class="width2-9"><?php echo CHtml::encode(CHtml::value($detail, 'warehouse.name')); ?></td>	
							</tr>
							<?php $reportCurrentStock = $currentStock; ?>
						<?php //endif; ?>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	<?php  endforeach; ?>
</table>