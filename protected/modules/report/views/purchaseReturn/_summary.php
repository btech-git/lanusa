<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 20% }
	.width1-3 { width: 20% }
	.width1-4 { width: 20% }
	.width1-5 { width: 20% }

	.width2-1 { width: 35% }
	.width2-2 { width: 10% }
	.width2-3 { width: 10% }
	.width2-4 { width: 10% }
	.width2-5 { width: 10% }
	.width2-6 { width: 25% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Retur Pembelian Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Retur #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Penerimaan Faktur Pembelian #</th>
		<th class="width1-4">Supplier</th>
    <!--<th class="width1-5">Gudang</th>-->
		<th class="width1-5">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="5">
			<table>
				<tr>
					<th class="width2-1">Nama Barang</th>
					<th class="width2-2">Ukuran</th>
					<th class="width2-3">Jml Retur</th>
					<th class="width2-4">Satuan</th>
					<th class="width2-5">Harga Satuan</th>
					<th class="width2-6">Total</th>
				</tr>
			</table>
		</td>
	</tr>
        <?php foreach ($purchaseReturnSummary->dataProvider->data as $header): ?>
			<tr class="items1">
				<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT)); ?></td>
				<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
				<td class="width1-3"><?php //echo CHtml::encode($header->purchaseInvoice->getCodeNumber(PurchaseInvoice::CN_CONSTANT)); ?></td>
				<td class="width1-4" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'receiveHeader.purchaseHeader.supplier.name' : 'receiveHeader.purchaseHeader.supplier.company')); ?></td>
			<!--<td class="width1-5" style="text-align: right"><?php //echo CHtml::encode(CHtml::value($header, 'receiveHeader.warehouse.name')); ?></td>-->
				<td class="width1-5" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
			</tr>
			<tr class="items2">
				<td colspan="5">
					<table>
						<?php foreach ($header->purchaseReturnDetails as $detail): ?>
							<tr>
								<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
								<td class="width2-2"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
<!--								<td class="width2-3" style="text-align: right"><?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantityPurchased'))); ?></td>-->
								<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
								<td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
								<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unitPrice'))); ?></td>
								<td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td style="border-top: 2px solid"></td>
							<td style="border-top: 2px solid"></td>
							<td style="border-top: 2px solid"></td>
							<td style="border-top: 2px solid;font-weight:bold">Sub Total</td>
							<td style="border-top: 2px solid"></td>
							<td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($header, 'subTotal')))); ?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>Tax  &nbsp <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($header, 'tax')))); ?> %</td>
							<td></td>
							<td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($header, 'calculatedTax')))); ?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>Ongkos Kirim</td>
							<td></td>
							<td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($header, 'shipping_fee')))); ?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-weight:bold">Grand Total</td>
							<td></td>
							<td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($header, 'grandTotal')))); ?></td>
						</tr>
					</table>
				</td>
			</tr>
        <?php endforeach; ?>
        <tr>
			<td class="width1-1" style="border-top: 1px solid"></td>
			<td class="width1-2" style="border-top: 1px solid"></td>
			<td class="width1-3" style="border-top: 1px solid"></td>
			<td class="width1-4" style="border-top: 1px solid; font-weight: bold">TOTAL RETUR</td>
			<td class="width1-5" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($this->reportGrandTotal($purchaseReturnSummary->dataProvider)))); ?></td>
        </tr>
</table>