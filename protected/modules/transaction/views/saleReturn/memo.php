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
        .hcolumn2header { width: 35% }
        .hcolumn2value { width: 65% }
        
        .sig1 { width: 25% }
        .sig2 { width: 50% }
        .sig3 { width: 25% }
');
?>

<div id="memoheader">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger">NOTA RETUR PENJUALAN</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Retur #</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($saleReturn->getCodeNumber(SaleReturnHeader::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleReturn, 'date')))); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Gudang</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($warehouse, 'name')); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Invoice #</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Customer</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($saleInvoice, 'deliveryHeader.saleHeader.customer.company')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th>Nama Barang</th>
        <th>Ukuran</th>
        <th>Jml Retur</th>
        <th>Satuan</th>
        <th>Harga Satuan</th>
        <th>Total</th>
    </tr>
    <?php foreach ($saleReturnDetails as $i => $detail): ?>
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope','with'=>'unit:resetScope')); ?>
        <tr class="titems">
            <td><?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?></td>
            <td style="text-align: center; width: 10%"><?php echo CHtml::encode(CHtml::value($detailProduct, 'size')); ?></td>
            <td style="text-align: center; width: 10%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'quantity'))); ?></td>
            <td style="text-align: center; width: 10%"><?php echo CHtml::encode(CHtml::value($detailProduct, 'unit.name')); ?></td>
            <td style="text-align: right; width: 15%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unitPrice'))); ?></td>
            <td style="text-align: right; width: 15%"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?></td>
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
		<td style="border-top: 2px solid" colspan="4"></td>
        <td style="border-top: 2px solid">Sub Total</td>
        <td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleReturn, 'subTotal')))); ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td>Tax &nbsp <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleReturn, 'tax')))); ?> %</td>
        <td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleReturn, 'calculatedTax')))); ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td>Ongkos Kirim</td>
        <td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleReturn, 'shipping_fee')))); ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td style="font-weight:bold">Grand Total</td>
        <td style="font-weight:bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleReturn, 'grandTotal')))); ?></td>
    </tr>
</table>

<div>
	Catatan: <?php echo CHtml::encode(CHtml::value($saleReturn, 'note')); ?>
</div>

<br />

<div style="text-transform: capitalize">
    Terbilang:
    <?php echo CHtml::encode(NumberWord::numberName(floor(CHtml::value($saleReturn, 'grandTotal')))); ?>
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
