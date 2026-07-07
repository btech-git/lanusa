<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 15% }
	.width1-2 { width: 10% }
	.width1-3 { width: 15% }
	.width1-4 { width: 20% }
	.width1-5 { width: 15% }
	.width1-6 { width: 25% }

	.width2-1 { width: 45% }
	.width2-2 { width: 10% }
	.width2-3 { width: 10% }
	.width2-4 { width: 5% }
	.width2-5 { width: 15% }
	.width2-6 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Invoice Penjualan</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Invoice #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Pengiriman #</th>
		<th class="width1-4">Customer</th>
		<th class="width1-5">Reference</th>
		<th class="width1-6">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="6">
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
	<?php foreach ($saleInvoiceSummary->dataProvider->data as $header): ?>
        <?php if (empty($header->saleReceiptDetails)): ?>
            <tr class="items1" style="color: red; font-weight: bold">
        <?php else: ?>
            <tr class="items1">
        <?php endif; ?>
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-3" style="text-align: right"><?php echo CHtml::encode($header->deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></td>
			<td class="width1-4" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'deliveryHeader.saleHeader.customer.name')); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'reference')); ?></td>
			<td class="width1-6" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="6">
				<table>
					<?php foreach ($header->deliveryHeader->deliveryDetails as $detail): ?>
						<tr>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
							<td class="width2-2" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
							<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
							<td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
							<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getUnitPrice($detail->deliveryHeader->sale_header_id))); ?></td>
							<td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getTotal())); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td colspan="5" style="border-top: 0px solid; font-weight: bold; text-align: right">Sub Total</td>
						<td class="width2-6" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->deliveryHeader->subTotal)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Discount</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->discount)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Uang Muka</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ($header->deliveryHeader->saleHeader->saleDownpayment === null) ? 0.00 : $header->deliveryHeader->saleHeader->saleDownpayment->amount)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">Ongkos Kirim</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->shipping_fee)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">PPN</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->calculatedTax)); ?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-weight: bold; text-align: right">GRAND TOTAL</td>
						<td class="width2-6" style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->grandTotal))); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="5" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PENJUALAN</td>
		<td class="width2-6" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($this->reportGrandTotal($saleInvoiceSummary->dataProvider)))); ?></td>
	</tr>
</table>