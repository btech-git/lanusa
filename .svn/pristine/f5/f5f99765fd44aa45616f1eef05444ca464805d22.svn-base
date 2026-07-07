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

    .hcolumn1header { width: 40% }
    .hcolumn1value { width: 60% }
	
    .hcolumn2header { width: 100% }
	
    .theader1 { width: 15%}
    .theader2 { width: 55%}
    .theader3 { width: 15%}
    .theader4 { width: 15%}
        
    .sig1 { width: 25% }
    .sig2 { width: 35% }
    .sig3 { width: 25% }
	
	.hcolumn1memoheader { width: 60% }
	.hcolumn2memoheader { width: 40% }
	
');
?>

<div id="memoheader">
    <div class="divtable">
        <div class="divtablecell hcolumn1memoheader">
            <div class="divtable">
                <div class="divtablerow">
                    <div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
                    <div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div>
                </div>
            </div>
        </div>

    </div>
</div>
<br /><br />

<div class="memonote" style="width:100%">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header">
                        Jakarta, 
                        <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($deliveryHeader, 'date')))); ?>
                    </div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">NOTA FAKTUR No. </div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Nomor Faktur Pajak</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($saleInvoice, 'reference')); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
                <div class="divtablerow" style="text-align: left; font-weight: 200;">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Kepada,</div>
                </div>
                <div class="divtablerow" style="text-align: left">
                    <div class="divtablecell info hcolumn2header"><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'saleHeader.customer.company')); ?></div>
                </div>
                <div class="divtablerow" style="text-align: left">
                    <div class="divtablecell info hcolumn2header"><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'saleHeader.customer.address')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th class="theader1" style="font-size:14px" colspan="2">BANYAKNYA</th>
        <th class="theader2" style="font-size:14px">NAMA BARANG</th>
        <th class="theader3" style="font-size:14px">HARGA</th>
        <th class="theader4" style="font-size:14px">Total</th>
    </tr>
    <?php $delivery = ($saleInvoice->deliveryHeader === null) ? DeliveryHeader::model() : $saleInvoice->deliveryHeader; ?>
    <?php $saleHeader = ($delivery->saleHeader === null) ? SaleHeader::model() : $delivery->saleHeader; ?>
    <?php foreach ($saleHeader->saleDetails as $i => $detail): ?>
        <?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>
        <tr class="titems">
            <td style="text-align: center; font-size:14px; border-right: none"><?php echo CHtml::encode(CHtml::value($detail, 'quantity')); ?></td>
            <td style="text-align: center; font-size:14px; border-left: none"><?php echo CHtml::encode(CHtml::value($detailProduct, 'unit.name')); ?></td>
            <td style="font-size:14px"><?php echo CHtml::encode(CHtml::value($detail, 'product_name')); ?></td>
            <td style="text-align: right; font-size:14px">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?>
            </td>
            <td style="text-align: right; font-size:14px">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($detail, 'total')))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php for ($j = 5, $i = $i % $j + 1; $j > $i; $j--): ?>
        <tr class="titems">
            <td style="border-right: none">&nbsp;</td>
            <td style="border-left: none">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?php if ($saleInvoice->is_non_tax): ?><td>&nbsp;</td><?php endif; ?>
            <td>&nbsp;</td>
        </tr>
    <?php endfor; ?>
    <tr>
        <td colspan ="4" style="border-top: 2px solid; font-weight: bold; font-size:14px; text-align: right">Sub Total</td>
        <td style="border-top: 2px solid; font-weight: bold; text-align: right; font-size:14px">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'subTotal'))); ?>
        </td>
    </tr>
    <?php if ($saleHeader->is_non_tax === 0): ?>
        <tr>
            <td colspan ="4" style="border-top: 2px solid; font-weight: bold; font-size:14px; text-align: right">DPP lain-lain</td>
            <td style="border-top: 2px solid; font-weight: bold; text-align: right; font-size:14px">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'costOfGoodsSold'))); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan ="4" style="font-size:14px; text-align: right">Diskon</td>
        <td style="text-align: right; font-size:14px">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'discount'))); ?>
        </td>
    </tr>

    <tr>
        <td colspan ="4" style="font-size:14px; text-align: right">Ongkos Kirim</td>
        <td style="text-align: right; font-size:14px">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'shipping_fee'))); ?>
        </td>
    </tr>

    <?php if ($saleInvoice->branch_id === 4): ?>
        <tr>
            <td colspan ="4" style="font-size:14px; text-align: right">
                PPN <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'tax'))); ?>%
            </td>
            <td style="text-align: right; font-size:14px">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleHeader, 'calculatedTax'))); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr class="titems">
        <td colspan="4" style="border-top: 2px solid; font-weight: bold; font-size:14px; text-align: right; border-left: 1px solid">Jumlah Rp.</td>
        <td style="font-weight: bold; text-align: right; font-size:14px; border-top: 2px solid;  border-right: 1px solid; border-left: 1px solid">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($saleHeader, 'grandTotal')))); ?>
        </td>
    </tr>
</table>

<br />
<br />

<div class="memosig">
    <div class="divtable">
        <div class="divtablecell sig1">
            <div style="font-size: 12px">TANDA TERIMA,</div>
        </div>
        <div class="divtablecell sig2" style="text-align:left;">
            <div style="font-weight: bold">PERHATIAN</div>
            <div style="font-weight: normal">Barang-barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</div>
        </div>
        <div class="divtablecell sig3">
            <div style="font-size: 12px">HORMAT KAMI,</div>
        </div>
    </div>
</div>
