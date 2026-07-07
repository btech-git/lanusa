<?php
Yii::app()->clientScript->registerScript('memo', '
    $("#header").addClass("hide");
    $("#mainmenu").addClass("hide");
    $(".breadcrumbs").addClass("hide");
    $("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/memo.css');
Yii::app()->clientScript->registerCss('memo', '
    .hcolumn1 { width: 50% }
    .hcolumn2 { width: 50% }

    .hcolumn1header { width: 35% }
    .hcolumn1value { width: 65% }
    .hcolumn2header { width: 65% }
    .hcolumn2value { width: 35% }

    .sig1 { width: 25% }
    .sig2 { width: 25% }
    .sig3 { width: 25% }
	.sig4 { width: 25% }
');
?>

<div id="memoheader" style="text-align: left">
    <div style="font-size: 20pt">Branch: <?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div>NPWP: <?php echo CHtml::encode(CHtml::value($branch, 'npwp')); ?></div>
	<div>Address: <?php echo CHtml::encode(CHtml::value($branch, 'address')); ?></div>
	<div>Phone: <?php echo CHtml::encode(CHtml::value($branch, 'phone')); ?></div>
	<div>Fax: <?php echo CHtml::encode(CHtml::value($branch, 'fax')); ?></div>
</div>

<br/><br/>

<div style="font-size: 16pt; text-align: center; font-weight:bold">PURCHASE ORDER</div>

<br />

<div class="memonote">
    <div class="divtable">
       <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Kepada :</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($purchase, 'supplier.name')); ?></div>
                </div><br/><br/>
				<div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">Telp :</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($purchase, 'supplier.phone')); ?></div>
				</div>
				<div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">Fax :</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($purchase, 'supplier.fax')); ?></div>
				</div>
			</div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
				<div class="divtablerow">
					 <div class="divtablecell info hcolumn2header" style="font-weight: bold">PO NO.     :</div>
					 <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($purchase->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></div>
				</div>
				<div class="divtablecell info hcolumn2header"style="color:black; border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; border-top: 1px solid"> Nomor PO ini harus dicantumkan pada semua kwintasi / invoice dan surat jalan</div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Tanggal PO   :</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchase, 'date')))); ?></div>
                </div>
				
			</div>
        </div>
    </div>
    <br />
</div>

<table class="memo">
    <tr id="theader">
		<th>No</th>
		<th style="font-size:12px">Quantity</th>
        <th style="font-size:12px">Nama Barang</th>
        <th style="font-size:12px">Harga Satuan</th>
		<th style="font-size:12px">Total</th>
    </tr>
    
    <?php foreach ($purchaseDetails as $i => $detail): ?>
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope','with'=>'unit:resetScope')); ?>
        <tr class="titems">
			<td style="border-bottom: 1px solid; text-align: center; font-size:12px"><?php echo $i+1; ?></td>
			<td style="border-bottom: 1px solid; text-align: center; font-size:12px"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', (CHtml::value($detail, 'quantity')))); ?></td>
            <td style="border-bottom: 1px solid; font-size:12px"><?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?></td>
            <td style="border-bottom: 1px solid; text-align: right; font-size:12px"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?></td>
			<td style="border-bottom: 1px solid; text-align: right; font-size:12px"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
        </tr>
    <?php endforeach; ?>
        
    <?php for ($j = 12, $i = $i % $j + 1; $j > $i; $j--): ?>
        <tr class="titems">
            <td style="border-bottom: 1px solid;" colspan="5">&nbsp;</td>
        </tr>
    <?php endfor; ?>
	<tr>
		<td colspan="4" style="border-left:2px solid; border-top: 2px solid; border-right:1px solid; font-weight: bold; text-align: right">Total Harga</td>
		<td style="border-top:2px solid; border-right:1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($purchase, 'subTotal'))); ?></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: right; border-left: 2px solid; border-right:1px solid; border-top: 1px solid; font-weight: bold">Diskon</td>
		<td style="border-top: 1px solid; border-right:1px solid; font-weight: bold; text-align: right; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($purchase, 'discount'))); ?></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: right; border-left: 2px solid; border-right:1px solid; border-top: 1px solid; font-weight: bold">PPN. <?php echo CHtml::encode(CHtml::value($purchase, 'tax')); ?>%</td>
		<td style="border-top: 1px solid; border-right:1px solid; font-weight: bold; text-align: right; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($purchase, 'calculatedTax'))); ?></td>
	</tr>
	<tr>
		<td colspan="4" style="border-left: 2px solid; border-top: 2px solid black; border-right:1px solid; font-weight: bold; text-align: right">Jumlah</td>
		<td style="border-top: 2px solid; border-right:1px solid; font-weight: bold; text-align: right; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($purchase, 'grandTotal'))); ?></td>
	</tr>
</table>

<br />

<div class="memosig">
    <div style="font-weight:bold; font-style: italic; border-left:2px solid; border-right:2px solid; border-bottom:2px solid; border-top:2px solid" class="divtable">
        <div style="border-right:2px solid" class="divtablecell sig1">
			<div>Purchasing,</div>
			<div style="border-top:2px solid"></div>
        </div>
        <div style="border-right:2px solid" class="divtablecell sig2">
            <div>Mengetahui,</div>
			<div style="border-top:2px solid"></div>
        </div>
        <div style="border-right:2px solid" class="divtablecell sig3">
            <div>Disetujui,</div>
			<div style="border-top:2px solid"></div>
        </div>
		 <div class="divtablecell sig4">
            <div>Supplier,</div>
			<div style="border-top:2px solid"><br/><br/><br/><?php echo CHtml::encode(CHtml::value($purchase, 'supplier.name')); ?></div>
        </div>
    </div>
</div>