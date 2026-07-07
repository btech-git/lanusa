<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 15% }
	.width1-2 { width: 15% }
	.width1-3 { width: 15% }
	.width1-4 { width: 25% }
	.width1-5 { width: 30% }
	
	.width2-1 { width: 15% }
	.width2-2 { width: 15% }
	.width2-3 { width: 40% }
	.width2-4 { width: 15% }
	.width2-5 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penerimaan Giro</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report" style="width: 100%">
	<tr id="header1">
		<th class="width1-1">Tanggal Terima</th>
		<th class="width1-2">Tanggal Jatuh Tempo</th>
		<th class="width1-3">Nomor Giro</th>
		<th class="width1-4">Customer</th>
		<th class="width1-5">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="5">
			<table>
				<tr>
				   <th class="width2-1">Tanda Terima #</th>
				   <th class="width2-2">Total</th>
				   <th class="width2-3">Bank</th>
				   <th class="width2-4">Cheque Number</th>
				   <th class="width2-5">Amount</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($saleChequeSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->receive_date))); ?></td>
			<td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->due_date))); ?></td>
			<td class="width1-3" style="text-align: center"><?php echo CHtml::encode($header->getCodeNumber(SaleCheque::CN_CONSTANT)); ?></td>
			<td class="width1-4" style="text-align: center"><?php echo CHtml::encode($header->customer->company); ?></td>
			<td class="width1-5" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="5">
				<table>
					<?php foreach ($header->saleChequeDetails as $detail): ?>
						<tr>
							<td class="width2-1"><?php echo CHtml::encode($detail->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></td>
							<td class="width2-2" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'saleReceiptHeader.totalInvoice'))); ?></td>
							<td class="width2-3"><?php echo CHtml::encode(CHtml::value($detail, 'bank')); ?></td>														
							<td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'cheque_number')); ?></td>
							<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'amount'))); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td style="border-top: 0px solid; font-weight: bold; text-align: right; font-size: small">TOTAL</td>
						<td class="width2-2" style="border-top: 1px solid; font-weight: bold; text-align: right; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->getTotalSaleReceipt())); ?></td>
						<td colspan="2" style="border-top: 0px solid; font-weight: bold; text-align: right; font-size: small"></td>
                                                <td style="border-top: 1px solid; font-weight: bold; text-align: right; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->totalAmount)); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
</table>