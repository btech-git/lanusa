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
    .sig2 { width: 25% }
    .sig3 { width: 25% }
    .sig4 { width: 25% }
');
?>

<div id="memoheader">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
    <div style="font-size: larger">Tanda Terima</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanda Terima #</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($saleReceipt->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleReceipt, 'date')))); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal Jatuh Tempo</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleReceipt, 'due_date')))); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Customer</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($customer, 'company')); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Catatan</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($saleReceipt, 'note')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th>Invoice #</th>
        <th>Tanggal</th>
        <th>Customer</th>
        <th>Total(Rp)</th>
        <th>Memo</th>
    </tr>
    <?php foreach ($saleReceiptDetails as $i => $detail): ?>
        <tr class="titems">
            <td><?php echo CHtml::encode($detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></td>
            <td style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleInvoice.date')))); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(CHtml::value($detail, 'saleInvoice.deliveryHeader.customer.company')); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'saleInvoice.totalInvoice'))); ?></td>
            <td style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan ="3" style="border-top: 2px solid;text-align: right;font-weight: bold">Total</td>
        <td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($saleReceipt, 'totalInvoice'))); ?></td>
        <td colspan = "2" style="border-top: 2px solid"></td>
    </tr>
</table>
