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
    .hcolumn2header { width: 40% }
    .hcolumn2value { width: 60% }

    .sig1 { width: 25% }
    .sig2 { width: 25% }
    .sig3 { width: 25% }
    .sig4 { width: 25% }
');
?>

<div id="memoheader">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
    <div style="font-size: larger">Pengeluaran Giro Pembelian</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Nota Giro #</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($purchaseCheque->getCodeNumber(PurchaseCheque::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal Giro</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchaseCheque, 'issue_date')))); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Jatuh Tempo</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($purchaseCheque, 'due_date')))); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Supplier</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($purchaseReceiptHeader, 'supplier.company')); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Catatan</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($purchaseCheque, 'note')); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Faktur #</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Tanggal Faktur</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($purchaseReceiptHeader, 'date')); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Giro #</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($purchaseCheque, 'cheque_number')); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Bank</div>
                    <div class="divtablecell info hcolumn2value"><?php //echo CHtml::encode(CHtml::value($bank, 'name')); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Jumlah Rp</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchaseCheque, 'amount'))); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th>Nomor Faktur</th>
        <th>Tanggal</th>
        <th>Supplier</th>
        <th>Total</th>
        <th>Memo</th>
    </tr>
    <?php foreach ($purchaseReceiptHeader->purchaseReceiptDetails as $i => $detail): ?>
        <tr class="titems">
            <td style="width: 15%"><?php echo CHtml::encode($detail->purchaseInvoiceHeader->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)); ?></td>
            <td style="width: 15%; text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'purchaseInvoice.date')))); ?></td>
            <td style="width: 25%"><?php echo CHtml::encode(CHtml::value($detail, 'purchaseInvoice.purchaseHeader.supplier.company')); ?></td>
            <td style="width: 15%; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'purchaseInvoice.totalPurchase'))); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td style="border-top: 2px solid"></td>
        <td style="border-top: 2px solid"></td>
        <td style="border-top: 2px solid;text-align: right">Total</td>
        <td style="border-top: 2px solid;text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', floor(CHtml::value($purchaseReceiptHeader, 'totalInvoice')))); ?></td>
        <td style="border-top: 2px solid"></td>
    </tr>
</table>

<div class="memosig">
    <div class="divtable">
        <div class="divtablecell sig1">
            <div>Dibuat Oleh,</div>
        </div>
        <div class="divtablecell sig2">
            <div>Diperiksa Oleh,</div>
        </div>
        <div class="divtablecell sig3">
            <div>Disetujui Oleh,</div>
        </div>
        <div class="divtablecell sig4">
            <div>Diterima Oleh,</div>
        </div>
    </div>
</div>