<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 15% }
	.width1-2 { width: 15% }
	.width1-3 { width: 20% }
	.width1-4 { width: 35% }
	.width1-5 { width: 15% }

	.width2-1 { width: 55% }
	.width2-2 { width: 15% }
	.width2-3 { width: 15% }
	.width2-4 { width: 15% }
	.width2-5 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'branch.name')); ?></div>
	<div style="font-size: larger">Laporan Pengiriman Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
			<th class="width1-1">Pengiriman #</th>
			<th class="width1-2">Tanggal</th>
			<th class="width1-5">Order #</th>
			<th class="width1-3">Customer</th>
			<th class="width1-4">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="5">
			<table>
				<tr>
					<th class="width2-5">Kode Barang</th>
					<th class="width2-1">Nama Barang</th>
					<th class="width2-2">Ukuran</th>
					<th class="width2-3">Jumlah</th>
					<th class="width2-4">Satuan</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($deliverySummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-5"><?php echo CHtml::encode($header->saleHeader->getCodeNumber(SaleHeader::CN_CONSTANT)); ?></td>
			<td class="width1-3" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'saleHeader.customer.company')); ?></td>
			<td class="width1-4" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="5">
				<table>
					<?php foreach ($header->deliveryDetails as $detail): ?>
						<tr>
							<td class="width2-5"><?php echo CHtml::encode(CHtml::value($detail, 'product.code')); ?></td>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
							<td class="width2-2"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
							<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
							<td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
</table>