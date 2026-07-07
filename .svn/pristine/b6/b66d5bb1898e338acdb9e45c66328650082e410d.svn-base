<?php
Yii::app()->clientScript->registerScript('invoice', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/memo.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/taxform.css');
Yii::app()->clientScript->registerCss('invoice', '
	.bordertopbottom
	{
		border-top: 2px solid;
		border-bottom: 2px solid;
	}

	.borderleftright
	{
		border-left: 2px solid;
		border-right: 2px solid;
	}

	.sig1 { width: 75% }
	.sig2 { width: 25% }
');
?>

<?php $count = count($saleInvoice->deliveryHeader->deliveryDetails); ?>

<?php $pageSize = 15; ?>
<?php $pageNumber = intval($count / $pageSize) + intval($count % $pageSize > 0); ?>
<?php $pageNumber = ($pageNumber > 0) ? $pageNumber : 1; ?>

<?php foreach (range(1, $pageNumber) as $num): ?>

	<div id="memoheader">
		<div class="divtable">
			<div class="divtablecell" style="width: 10%">&nbsp;</div>
			<div class="divtablecell" style="width: 55%">
				<div>&nbsp;</div>
				<div style="font-size: 24px">FAKTUR PAJAK</div>
			</div>
			<div class="divtablecell" style="width: 35%; text-align: left; font-size: 9px">
				<table style="border: 1px solid">
					<tr>
						<td style="vertical-align: top; width: 30%">Lembar Ke 1:</td>
						<td style="width: 70%">Untuk Pembeli BKP/Penerima JKP sebagai bukti pajak Masukan</td>
					</tr>
					<tr>
						<td style="vertical-align: top">Lembar Ke 2:</td>
						<td>Untuk PKP sebagai bukti pajak Keluaran</td>
					</tr>
					<tr>
						<td style="vertical-align: top">Lembar Ke 3:</td>
						<td>Untuk Arsip/File</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<table class="formnote">
		<tr>
			<td style="width: 35%; font-weight: bold">Kode dan Nomor Seri Faktur Pajak</td>
			<td style="width: 35%"><?php echo CHtml::encode(CHtml::value($saleInvoice, 'reference'));?></td>
			<td>F: <?php echo CHtml::encode($saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></td>
		</tr>
	</table>

	<table class="formnote">
		<caption style="font-weight: bold">Pengusaha Kena Pajak</caption>
		<tr>
			<td style="width: 20%">Nama</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($branch, 'address')); ?>, 
				<?php echo CHtml::encode(CHtml::value($branch, 'city')); ?> 
				<?php echo CHtml::encode(CHtml::value($branch, 'zip_code')); ?>
			</td>
		</tr>
		<tr>
			<td>NPWP</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($branch, 'npwp')); ?></td>
		</tr>
	</table>

	<table class="formnote">
		<caption style="font-weight: bold">Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak</caption>
		<tr>
			<td style="width: 20%">Nama</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'saleHeader.customer.company')); ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'saleHeader.customer.address')); ?></td>
		</tr>
		<tr>
			<td>NPWP</td>
			<td>:</td>
			<td><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'saleHeader.customer.npwp')); ?></td>
		</tr>
	</table>

	<table class="memo formdetail">
		<tr id="theader" style="font-size: 14px">
			<th style="width: 0">No. Urut</th>
			<th>Nama Barang Kena Pajak / Jasa Kena Pajak</th>
			<th style="width: 200px">Harga Jual/Penggantian/Uang Muka/Termin (Rp)</th>
		</tr>

		<?php $counter = 0; ?>
		<?php foreach ($saleInvoice->deliveryHeader->saleHeader->saleDetails as $i => $detail): ?>
			<?php if ($i <= $num * $pageSize - 1 && $i >= ($num - 1) * $pageSize): ?>
				<tr class="titems" style="font-size: 14px">
					<td><?php echo $i + 1; ?></td>
					<td><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?> 
						<?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?>
					</td>
					<td style="text-align: right">
						<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'total'))); ?>
					</td>
				</tr>
				<?php $counter++; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php for ($i = 0; $i < $pageSize - $counter; $i++): ?>
			<tr class="titems">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		<?php endfor; ?>

		<tr class="titems" style="font-size: 14px">
			<td class="formsummary" colspan="2">Harga Jual/<span style="text-decoration:line-through">Penggantian/Uang Muka/Termin</span> *)</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.subTotal'))); ?></td>
		</tr>
		<tr class="titems" style="font-size: 14px">
			<td class="formsummary" colspan="2">Dikurangi Potongan Harga</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.calculatedDiscount'))); ?></td>
		</tr>
		<tr class="titems" style="font-size: 14px">
			<td class="formsummary" colspan="2">Dikurangi Uang Muka yang telah diterima</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.salesDownpayment.amount')));    ?></td>
		</tr>
		<tr class="titems" style="font-size: 14px">
			<td class="formsummary" colspan="2">Dasar Pengenaan Pajak</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.totalBeforeTax'))); ?></td>
		</tr>
		<tr class="titems" style="font-size: 14px">
			<td class="formsummary" colspan="2">PPN = 10 % X Dasar Pengenaan Pajak</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.calculatedTax'))); ?></td>
		</tr>
	</table>

	<div class="divtable">
		<div class="divtablecell sig1" style="text-align: left; font-size: 14px">
			Pajak Penjualan Atas Barang Mewah
			<table style="width: 250px; border-spacing: 0pt; margin: 0">
				<tr>
					<td class="bordertopbottom borderleftright" style="text-align: center; font-size: 11px">Tarif</td>
					<td class="bordertopbottom" style="text-align: center; font-size: 11px">DPP</th>
					<td class="bordertopbottom borderleftright" style="text-align: center; font-size: 11px">PPnBM</td>
				</tr>
				<tr>
					<td class="borderleftright" style="text-align: center; font-size: 11px">..........%</td>
					<td style="text-align: center; font-size: 11px">Rp........</td>
					<td class="borderleftright" style="text-align: center; font-size: 11px">Rp........</td>
				</tr>
				<tr>
					<td class="borderleftright" style="text-align: center; font-size: 11px">..........%</td>
					<td style="text-align: center; font-size: 11px">Rp........</td>
					<td class="borderleftright" style="text-align: center; font-size: 11px">Rp........</td>
				</tr>
				<tr>
					<td class="borderleftright" style="text-align: center; font-size: 11px">..........%</td>
					<td style="text-align: center; font-size: 11px">Rp........</td>
					<td class="borderleftright" style="text-align: center; font-size: 11px">Rp........</td>
				</tr>
				<tr>
					<td class="borderleftright" style="text-align: center; font-size: 11px">..........%</td>
					<td style="text-align: center; font-size: 11px">Rp........</td>
					<td class="borderleftright" style="text-align: center; font-size: 11px">Rp........</td>
				</tr>
				<tr>
					<td class="bordertopbottom borderleftright" style="font-size: 11px">Jumlah</td>
					<td class="bordertopbottom" style="font-size: 11px"></td>
					<td class="bordertopbottom borderleftright" style="font-size: 11px">Rp........</td>
				</tr>
			</table>
			*) Coret yang tidak perlu
		</div>
		<div class="divtablecell sig2">
			<div>&nbsp;</div>
			<div style="font-size: 14px; text-align: center">Jakarta, <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleInvoice, 'saleHeader.date')))); ?></div>
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<div style="font-size: 14px; text-align: center">Ivanov Alexander</div>
		</div>
	</div>

	<?php if ($num < $pageNumber): ?>
		<div style="page-break-after: always"></div>
	<?php endif; ?>

<?php endforeach; ?>