<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 20% }
	.width1-4 { width: 15% }
	.width1-5 { width: 15% }
	.width1-6 { width: 15% }

	.width2-1 { width: 20% }
	.width2-2 { width: 20% }
	.width2-3 { width: 10% }
        .width2-4 { width: 50% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Pembayaran Penjualan Barang</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Pembayaran #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">TT Penjualan #</th>
		<th class="width1-4">Tanggal TT</th>
		<th class="width1-5">Customer</th>
		<th class="width1-6">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan=6">
			<table>
				<tr>
					<th class="width2-1">Nama Akun</th>
					<th class="width2-2">Jenis Pembayaran</th>
					<th class="width2-3">Jumlah (Rp)</th>
					<th class="width2-4">Memo</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($salePaymentSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(SalePaymentHeader::CN_CONSTANT)); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
			<td class="width1-3" style="text-align: right"><?php echo CHtml::encode($header->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></td>
			<td class="width1-4" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'saleReceiptHeader.date')); ?></td>
			<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'saleReceiptHeader.customer.company')); ?></td>
			<td class="width1-6" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="6">
				<table>
					<?php foreach ($header->salePaymentDetails as $detail): ?>
						<tr>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?></td>
							<td class="width2-2"><?php echo CHtml::encode(CHtml::value($detail, 'paymentType.name')); ?></td>
							<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->amount)); ?></td>
							<td class="width2-4" style="text-align: left"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td class="width2-1" style="border-top: 0px solid; font-weight: bold"></td>
						<td class="width2-2" style="border-top: 0px solid; font-weight: bold; text-align: right">Total Tanda Terima</td>
						<td class="width2-3" style="border-top: 1px solid;font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0',ceil($header->totalSale))); ?></td>
						<td class="width2-4" style="border-top: 0px solid"></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td class="width1-1" style="border-top: 1px solid"></td>
		<td class="width1-2" style="border-top: 1px solid;font-weight: bold; text-align: right">TOTAL PEMBAYARAN</td>
		<td class="width1-3" style="border-top: 1px solid;font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($this->reportGrandTotal($salePaymentSummary->dataProvider)))); ?></td>
		<td class="width1-4" style="border-top: 1px solid"></td>
		<td class="width2-5" style="border-top: 1px solid; font-weight: bold"></td>
		<td class="width1-6" style="border-top: 1px solid; font-weight: bold; text-align: right"></td>
	</tr>
</table>