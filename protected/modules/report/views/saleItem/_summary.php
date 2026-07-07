<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 65% }
	.width1-3 { width: 15% }

	.width2-1 { width: 15% }
	.width2-2 { width: 15% }
	.width2-3 { width: 35% }
	.width2-4 { width: 5% }
	.width2-5 { width: 15% }
	.width2-6 { width: 15% }
');
//start date and end date are empty and hide all the detail
$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penjualan Barang berdasarkan Produk</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Kategori</th>
		<th class="width1-2">Nama Produk</th>
		<th class="width1-3">Ukuran</th>
	</tr>
	<tr id="header2">
		<td colspan="3">
			<table>
				<tr>
				   <th class="width2-1">Penjualan #</th>
				   <th class="width2-2">Tanggal</th>
				   <th class="width2-3">Pelanggan</th>
				   <th class="width2-4">Jumlah</th>
				   <th class="width2-5">Harga</th>
				   <th class="width2-6">Total</th>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach ($saleItemSummary->dataProvider->data as $header): ?>
	  <?php if($header->saleDetails !=null) : ?> 
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'category.name')); ?></td>
			<td class="width1-2"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
			<td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'size')); ?></td>
		</tr>
		<tr class="items2">
			<td colspan="3">
				<table>
					<?php $totalQuantitySales = 0; $totalSales = 0.00; ?>
					<?php foreach ($header->saleDetails as $detail): ?>
						<?php $saleHeader = $detail->saleHeader(array('scopes' => 'resetScope', 'with' => 'customer:resetScope')); ?>
						<?php 
							if ($detail->saleHeader !== null 
										&& $detail->saleHeader->date >= $startDate
										&& $detail->saleHeader->date <= $endDate
										&& $detail->saleHeader->branch_id == $branch
										):	//relation doesn't filter the attributes from filter?>
						
						<?php //if (CHtml::value($saleHeader, 'date') >= $startDate): ?>
							<tr>
								<td class="width2-1">
									<?php echo ($detail->saleHeader != NULL)
										? CHtml::encode($saleHeader->getCodeNumber(SaleHeader::CN_CONSTANT))
										: 'No Sale Header'; ?></td>
								<td class="width2-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleHeader, 'date')))); ?></td>
								<td class="width2-3"><?php echo CHtml::encode(CHtml::value($saleHeader, 'customer.company')); ?></td>														
								<td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity'))); ?></td>
								<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?></td>
								<td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
							</tr>
							<?php $totalQuantitySales += CHtml::value($detail, 'quantity'); ?>
							<?php $totalSales += CHtml::value($detail, 'total'); ?>
						<?php endif;?>
					<?php endforeach; ?>
					<tr>
						<td colspan="3" style="border-top: 0px solid; font-weight: bold; text-align: right; font-size: small">TOTAL</td>
						<td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: right; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($totalQuantitySales))); ?></td>
						<td colspan="2" style="border-top: 1px solid; font-weight: bold; text-align: right; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($totalSales))); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	  <?php endif; ?>
	<?php endforeach; ?>
</table>