<?php
Yii::app()->clientScript->registerCss('_report', '
        .width1-1 { width: 70% }
        .width1-2 { width: 30% }
        
        .width2-1 { width: 30% }
        .width2-2 { width: 30% }
        .width2-3 { width: 30% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penjualan Barang berdasarkan Customer</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
			<th class="width1-1">Nama Perusahaan</th>
			<th class="width1-2">Nama Customer</th>
	</tr>
	<tr id="header2">
		<td colspan="2">
			<table>
				<tr>
					<th class="width2-1">Penjualan #</th>
					<th class="width2-2">Tanggal</th>
					<th class="width2-3">Total</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($saleCustomerSummary->dataProvider->data as $header): ?>
			<?php //if($header->saleHeaders != null): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'company')); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="2">
				<table>
					<?php $totalSales = 0.00; ?>
					<?php foreach ($header->saleHeaders as $detail): ?>
						<?php if ($detail->date >= $startDate): ?>
							<tr>
								<td class="width2-1"><?php echo CHtml::encode($detail->getCodeNumber(SaleHeader::CN_CONSTANT)); ?></td>
								<td class="width2-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))); ?></td>
								<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'grandTotal'))); ?></td>
							</tr>
							<?php $totalSales += CHtml::value($detail, 'grandTotal'); ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<tr>
						<td class="width2-1" style="border-top: 1px solid"></td>
						<td class="width2-2" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PENJUALAN</td>
						<td class="width2-3" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($totalSales))); ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php //endif; ?>
	<?php endforeach; ?>
</table>
		
		
