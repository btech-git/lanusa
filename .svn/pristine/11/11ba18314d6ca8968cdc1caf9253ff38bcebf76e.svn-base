<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 65% }

	.width2-1 { width: 10% }
	.width2-2 { width: 30% }
	.width2-3 { width: 15% }
	.width2-4 { width: 15% }
	.width2-5 { width: 30% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Jurnal Voucher</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Voucher #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="3">
			<table>
				<tr>
					<th class="width2-1">Kode Akun</th>
					<th class="width2-2">Nama Akun</th>
					<th class="width2-3">Debit</th>
					<th class="width2-4">Kredit</th>
					<th class="width2-5">Memo</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($journalVoucherSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(JournalVoucherHeader::CN_CONSTANT)); ?></td>
				<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
				<td class="width1-3" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="3">
				<table>
					<?php foreach ($header->journalVoucherDetails as $detail): ?>
						<tr>
							<td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'account_id')); ?></td>
							<td class="width2-2"><?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?></td>
							<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'debit'))); ?></td>
							<td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'credit'))); ?></td>
							<td class="width2-5" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($detail, 'memo'))); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td class="width2-1" style="border-top: 0px solid"></td>
						<td class="width2-2" style="border-top: 0px solid; font-weight: bold; text-align: right">TOTAL</td>
						<td class="width2-3" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->totalDebit))); ?></td>
						<td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($header->totalCredit))); ?></td>
						<td class="width2-5" style="border-top: 0px solid"></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endforeach; ?>
</table>