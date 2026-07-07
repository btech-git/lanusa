<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 20% }
	.width1-4 { width: 15% }
	.width1-5 { width: 20% }
	.width1-6 { width: 10% }

	.width2-1 { width: 60% }
	.width2-2 { width: 15% }
	.width2-3 { width: 10% }
	.width2-4 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penerimaan Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Penerimaan #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Pembelian #</th> 
		<th class="width1-4">Faktur #</th> 
		<th class="width1-5">Supplier</th>
		<th class="width1-6">Gudang</th>
	</tr>
	<tr id="header2">
		<td colspan="6">
			<table>
				<tr>
					<th class="width2-5">Kode Barang</th>
					<th class="width2-1">Nama Barang</th>
					<th class="width2-2">Ukuran</th>
					<th class="width2-3">Satuan</th>
					<th class="width2-4">Jumlah Terima</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($receiveSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-3"><?php echo CHtml::encode($header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
			<td class="width1-4"><?php echo CHtml::encode(CHtml::value($header, 'reference')); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'purchaseHeader.supplier.name' : 'purchaseHeader.supplier.company')); ?></td>
			<td class="width1-6" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'warehouse.name')); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="6">
				<table>
					<?php foreach ($header->receiveDetails as $detail): ?>
						<tr>
							<td class="width2-5"><?php echo CHtml::encode(CHtml::value($detail, 'product.code')); ?></td>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
							<td class="width2-2" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
							<td class="width2-3"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
							<td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
</table>