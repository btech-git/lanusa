<?php
Yii::app()->clientScript->registerScript('memo', '
        $("#header").addClass("hide");
        $("#mainmenu").addClass("hide");
        $(".breadcrumbs").addClass("hide");
        $("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/transaction/memo.css');
Yii::app()->clientScript->registerCss('memo', '
        .hcolumn1 { width: 50% }
        .hcolumn2 { width: 50% }
        
        .hcolumn1header { width: 35% }
        .hcolumn1value { width: 65% }
        .hcolumn2header { width: 45% }
        .hcolumn2value { width: 55% }
        
        .sig1 { width: 25% }
        .sig2 { width: 50% }
        .sig3 { width: 25% }
');
?>

<div id="memoheader">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')) ?></div>
	<div style="font-size: larger">NOTA RETUR PEMBELIAN</div>
</div>

<br />

<div class="memonote">
	<div class="divtable">
		<div class="divtablecell hcolumn1">
			<div class="divtable">
				<div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">Retur #</div>
					<div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($purchaseReturn->getCodeNumber(PurchaseReturnHeader::CN_CONSTANT)); ?></div>
				</div>
				<div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal</div>
					<div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchaseReturn, 'date')))); ?></div>
				</div>
				 <div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">Supplier</div>
					<div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($receiveHeader, 'purchaseHeader.supplier.company')); ?></div>
				</div>
			</div>
			</div>
                <div class="divtablecell hcolumn2">
					<div class="divtable">
						<div class="divtablerow">
							<div class="divtablecell info hcolumn2header" style="font-weight: bold">Faktur Pembelian #</div>
							<div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?></div>
						</div>
						<div class="divtablerow">
							<div class="divtablecell info hcolumn2header" style="font-weight: bold">Gudang</div>
							<div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($warehouse, 'name')); ?></div>
						</div>
						
					</div>
                </div>
        </div>
</div>

<br />

<table class="memo">
        <tr id="theader">
			<th  style="font-size:12px">Nama Barang</th>
			<th  style="font-size:12px">Ukuran</th>
			<th  style="font-size:12px">Jumlah Retur</th>
			<th  style="font-size:12px">Satuan</th>
			<th  style="font-size:12px">Harga Satuan</th>
			<th  style="font-size:12px">Total</th>
        </tr>
        <?php foreach ($purchaseReturnDetails as $i=>$detail): ?>
			<?php $detailProduct = $detail->product(array('scopes' => 'resetScope','with'=>'unit:resetScope')); ?>
			<tr class="titems">
				<td style="text-align: left"><?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?></td>
				<td style="text-align: center; font-size:12px; width: 15%"><?php echo CHtml::encode(CHtml::value($detailProduct, 'size')); ?></td>
				<td style="text-align: center; font-size:12px; width: 10%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00',(CHtml::value($detail, 'quantity')))); ?></td>
				<td style="text-align: center; font-size:12px; width: 5%"><?php echo CHtml::encode(CHtml::value($detailProduct, 'unit.name')); ?></td>
				<td style="text-align: right; font-size:12px; width: 10%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unitPrice'))); ?></td>
				<td style="text-align: right; font-size:12px; width: 15%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
			</tr>
        <?php endforeach; ?>
        <?php for ($j = 5, $i = $i % $j + 1; $j > $i; $j--): ?>
			<tr class="titems">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
        <?php endfor; ?>
		<tr>
			<td colspan ="5" style="border-top: 2px solid;font-weight:bold; text-align: right">Sub Total</td>
			<td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($purchaseReturn, 'subTotal')))); ?></td>
        </tr>
		<tr>
			<td colspan ="5" style="text-align: right">Tax &nbsp <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($purchaseReturn, 'tax')))); ?> %</td>
			<td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($purchaseReturn, 'calculatedTax')))); ?></td>
        </tr>
        <tr>
			<td colspan ="5" style="text-align: right">Ongkos Kirim</td>
			<td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($purchaseReturn, 'shipping_fee')))); ?></td>
        </tr>
		 <tr>
			<td colspan ="5" style="font-weight:bold; text-align: right">Grand Total</td>
			<td style="font-weight:bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', floor(CHtml::value($purchaseReturn, 'grandTotal')))); ?></td>
        </tr>
</table>

<div>
	Catatan:
	<?php echo CHtml::encode(CHtml::value($purchaseReturn, 'note')); ?>
</div>

<br />

<div style="text-transform: capitalize">
	Terbilang:
	<?php echo CHtml::encode(NumberWord::numberName(floor(CHtml::value($purchaseReturn, 'grandTotal')))); ?>
	rupiah
</div>

<br />

<div class="memosig">
	<div class="divtable">
		<div class="divtablecell sig1">
			<div>Penerima,</div>
		</div>
		<div class="divtablecell sig2">
			&nbsp;
		</div>
		<div class="divtablecell sig3">
			<div>Hormat Kami,</div>
		</div>
	</div>
</div>