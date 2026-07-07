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

    .hcolumn1header { width: 45% }
    .hcolumn1value { width: 55% }
    .hcolumn2header { width: 45% }
    .hcolumn2value { width: 55% }

    .sig1 { width: 25% }
    .sig2 { width: 25% }
    .sig3 { width: 25% }
    .sig4 { width: 25% }
');
?>

<div id="memoheader">
    <div class="divtable">
		<div class="divtablecell hcolumn1memoheader">
			<div class="divtable">
				<div class="divtablerow">
					<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
					<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
					<div style="font-size: larger">Penerimaan Faktur Pembelian</div>
				</div>
			</div>
		</div>
		<div class="divtablecell hcolumn2memoheader">
			<div class="divtable">
				<div class="divtablerow" style="text-align: left; font-weight: 200;">
					<div>Jakarta, <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchaseInvoice, 'date')))); ?></div>
					<div style="font-weight: bold">Kepada,</div>
					<div><?php echo CHtml::encode(CHtml::value($purchaseHeader, 'supplier.company')); ?></div>
					<div><?php echo CHtml::encode(CHtml::value($purchaseHeader, 'supplier.address')); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">FAKTUR No : </div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($purchaseInvoice->getCodeNumber(PurchaseInvoice::CN_CONSTANT)); ?></div>
                </div>
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Pembelian #</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode($purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></div>
                </div>-->
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Faktur #</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(CHtml::value($purchaseInvoice, 'reference')); ?></div>
                </div>-->
<!--				<div class="divtablerow">
					<div>Jakarta, <?php //echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchaseHeader, 'date')))); ?></div>
					<div>Kepada, <?php //echo CHtml::encode(CHtml::value($purchaseHeader, 'supplier.company')); ?></div>
					<div><?php //echo CHtml::encode(CHtml::value($purchaseHeader, 'supplier.address')); ?></div>
				</div>-->
            </div>
        </div>
    </div>
</div>
<br />
<table class="memo">
    <tr id="theader">
		<th style="font-size:12px; width: 10%">Banyaknya</th>
		<th style="font-size:12px; width: 10%">Satuan</th>
        <th style="font-size:12px">NAMA BARANG</th>
        <th style="font-size:12px; width: 10%">Ukuran</th>
        <th style="font-size:12px; width: 15%">Harga Satuan</th>
    </tr>
   <?php foreach ($purchaseHeader->purchaseDetails as $i=>$detail): ?>
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope','with'=>'unit:resetScope')); ?>
        <tr class="titems">
			<td style="text-align: center; font-size:12px"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', (CHtml::value($detail, 'quantity')))); ?></td>
            <td style="text-align: center; font-size:12px"><?php echo CHtml::encode(CHtml::value($detailProduct, 'unit.name')); ?></td>
			<td style="font-size:12px"><?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?></td>
            <td style="text-align: center; font-size:12px"><?php echo CHtml::encode(CHtml::value($detailProduct, 'size')); ?></td>
            <td style="text-align: right; font-size:12px"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?></td>
        </tr>
    <?php endforeach; ?>
    <?php for ($j = 12, $i = $i % $j + 1; $j > $i; $j--): ?>
        <tr class="titems">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    <?php endfor; ?>
</table>
<br/>
<div class="memosig">
    <div class="divtable">
        <div class="divtablecell sig1">
            <div>Tanda Terima,</div>
        </div>
        <div class="divtablecell sig2">
            <div>Hormat kami,</div>
        </div>
    </div>
</div>