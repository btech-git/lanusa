<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 25% }
	.width1-4 { width: 40% }

	.width2-1 { width: 35% }
	.width2-2 { width: 15% }
	.width2-3 { width: 10% }
	.width2-4 { width: 5% }
	.width2-5 { width: 15% }
	.width2-6 { width: 15% }
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
		<th class="width1-3">Supplier</th>
		<th class="width1-4">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="4">
			<table>
				<tr>
					<th class="width2-1">Nama Barang</th>
					<th class="width2-2">Ukuran</th>
					<th class="width2-3">Jumlah</th>
					<th class="width2-4">Satuan</th>
					<th class="width2-5">Harga Satuan</th>
					<th class="width2-6">Total</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($purchaseSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-3" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'supplier.name')); ?></td>
			<td class="width1-4" style="text-align: left;"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="4">
				<table>
					<?php foreach ($header->purchaseDetails as $detail): ?>
						<tr>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
							<td class="width2-2"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
							<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
							<td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
							<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?></td>
							<td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td colspan="5" style="border-top: 1px solid; font-weight: bold; text-align: right">Sub Total</td>
						<td class="width2-6" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->subTotal)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Diskon</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($header, 'discount'))); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Tax (<?php echo CHtml::encode(CHtml::value($header, 'tax')); ?> %)</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->calculatedTax)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Ongkos Kirim</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->shipping_fee)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Grand Total</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->grandTotal))); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td class="width1-1" style="border-top: 1px solid"></td>
		<td class="width1-2" style="border-top: 1px solid"></td>
		<td class="width2-3" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PEMBELIAN</td>
		<td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($this->reportGrandTotal($purchaseSummary->dataProvider)))); ?></td>
	</tr>
</table>