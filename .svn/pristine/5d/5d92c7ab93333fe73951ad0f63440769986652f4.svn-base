<?php
Yii::app()->clientScript->registerScript('invoice', '
        $("#header").addClass("hide");
        $("#mainmenu").addClass("hide");
        $(".breadcrumbs").addClass("hide");
        $("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/memo.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/taxform.css');
Yii::app()->clientScript->registerCss('invoice', '
        .bordertopbottom
        {
			border-top: 1px solid;
			border-bottom: 1px solid;
        }
        
        .borderleftright
        {
			border-left: 1px solid;
			border-right: 1px solid;
        }
        
        .sig1 { width: 75% }
        .sig2 { width: 25% }
');
?>

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

<br />

<table class="formnote">
	<tr>
		<td style="width: 35%; font-weight: bold">Kode dan Nomor Seri Faktur Pajak</td>
		<td style="width: 35%"></td>
		<td>F: <?php echo CHtml::encode($saleDownpayment->getCodeNumber(SaleDownpayment::CN_CONSTANT)); ?></td>
		
	</tr>
</table>

<table class="formnote">
	<caption style="font-weight: bold">Pengusaha Kena Pajak</caption>
	<tr>
		<td style="width: 25%">Nama</td>
		<td><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td><?php echo CHtml::encode(CHtml::value($branch, 'address')); ?>, <?php echo CHtml::encode(CHtml::value($branch, 'city')); ?> <?php echo CHtml::encode(CHtml::value($branch, 'zip_code')); ?></td>
	</tr>
	<tr>
		<td>NPWP</td>
		<td><?php echo CHtml::encode(CHtml::value($branch, 'npwp')); ?></td>
	</tr>
</table>

<table class="formnote">
	<caption style="font-weight: bold">Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak</caption>
	<tr>
		<td style="width: 25%">Nama</td>
		<td><?php echo CHtml::encode(CHtml::value($customer, 'company')); ?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td><?php echo CHtml::encode(CHtml::value($customer, 'address')); ?></td>
	</tr>
	<tr>
		<td>NPWP</td>
		<td><?php echo CHtml::encode(CHtml::value($customer, 'npwp')); ?></td>
	</tr>
</table>

<table class="memo formdetail">
	<tr id="theader">
		<th style="width: 0">No. Urut</th>
		<th style="width: 0">Nomor Pajak</th>
		<th>Nama Barang Kena Pajak / Jasa Kena Pajak</th>
		<th style="width: 200px">Harga Jual (Rp)</th>
	</tr>
	<tr class="titems">
		<td><?php echo 1; ?></td>
		<td><?php echo CHtml::encode(CHtml::value($saleDownpayment, 'tax_number')); ?></td>
		<td><?php echo CHtml::encode(CHtml::value($saleDownpayment, 'note')); ?></td>
		<td style="text-align: right;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleDownpayment, 'amount'))); ?></td>
	</tr>
        <?php for ($j = 15, $i = 1 % $j + 1; $j > $i; $j--): ?>
			<tr class="titems">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
        <?php endfor; ?>
        <tr class="titems">
			<td class="formsummary" colspan="3">Harga Jual/<span style="text-decoration: line-through">Penggantian/Uang Muka/Termin</span> *)</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleDownpayment, 'amount'))); ?></td>
        </tr>
        <tr class="titems">
			<td class="formsummary" colspan="3">Dikurangi Potongan Harga</td>
			<td class="formsummary" style="text-align: right">0.00</td>
        </tr>
        <tr class="titems">
			<td class="formsummary" colspan="3">Dikurangi Uang Muka yang telah diterima</td>
			<td class="formsummary" style="text-align: right">0.00</td>
        </tr>
        <tr class="titems">
			<td class="formsummary" colspan="3">Dasar Pengenaan Pajak</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleDownpayment, 'amount'))); ?></td>
        </tr>
        <tr class="titems">
			<td class="formsummary" colspan="3">PPN = 10 % X Dasar Pengenaan Pajak</td>
			<td class="formsummary" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleDownpayment, 'calculatedTax'))); ?></td>
        </tr>
</table>

<div class="divtable">
	<div class="divtablecell sig1" style="text-align: left">
			Pajak Penjualan Atas Barang Mewah
			<table style="width: 250px; border-spacing: 0pt; margin: 0">
					<tr>
						<td class="bordertopbottom borderleftright" style="text-align: center; font-size: larger">Tarif</td>
						<td class="bordertopbottom" style="text-align: center; font-size: larger">DPP</th>
						<td class="bordertopbottom borderleftright" style="text-align: center; font-size: larger">PPnBM</td>
					</tr>
					<tr>
						<td class="borderleftright">..........%</td>
						<td>Rp........</td>
						<td class="borderleftright">Rp........</td>
					</tr>
					<tr>
						<td class="borderleftright">..........%</td>
						<td>Rp........</td>
						<td class="borderleftright">Rp........</td>
					</tr>
					<tr>
						<td class="borderleftright">..........%</td>
						<td>Rp........</td>
						<td class="borderleftright">Rp........</td>
					</tr>
					<tr>
						<td class="borderleftright">..........%</td>
						<td>Rp........</td>
						<td class="borderleftright">Rp........</td>
					</tr>
					<tr>
						<td class="bordertopbottom borderleftright" style="font-size: larger">Jumlah</td>
						<td class="bordertopbottom" style="font-size: larger"></td>
						<td class="bordertopbottom borderleftright" style="font-size: larger">Rp........</td>
					</tr>
			</table>
			*) Coret yang tidak perlu
	</div>
	<div class="divtablecell sig2">
		<div>&nbsp;</div>
		<div style="font-size: 14px; text-align: center">Jakarta, <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($saleDownpayment->date))); ?></div>
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<div style="text-decoration: underline; font-size: 14px; text-align: center">Ivanov Alexander</div>
	</div>
</div>