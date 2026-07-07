<?php
Yii::app()->clientScript->registerScript('memo', '
    $("#header").addClass("hide");
    $("#mainmenu").addClass("hide");
    $(".breadcrumbs").addClass("hide");
    $("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/memo.css');
Yii::app()->clientScript->registerCss('memo', '
    .hcolumn1 { width: 60% }
    .hcolumn2 { width: 50% }

    .hcolumn1header { width: 35% }
    .hcolumn1value { width: 65% }
    .hcolumn2header { width: 45% }
    .hcolumn2value { width: 55% }

    .sig1 { width: 25% }
    .sig2 { width: 25% }
    .sig3 { width: 25% }
    .sig4 { width: 25% }
');
?>

<div id="memoheader">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
    <div style="font-size: larger">Penerimaan Giro Penjualan</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Nota Giro #</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($saleCheque->getCodeNumber(SaleCheque::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal Terima</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleCheque, 'receive_date')))); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Jatuh Tempo</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($saleCheque, 'due_date')))); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Catatan</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(CHtml::value($saleCheque, 'note')); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Cabang</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($saleCheque->branch->name); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Customer</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($saleCheque->saleChequeDetails[0]->saleReceiptHeader->customer->company); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Nama Pelanggan</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($saleCheque->saleChequeDetails[0]->saleReceiptHeader->customer->name); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Alamat Pelanggan</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode($saleCheque->saleChequeDetails[0]->saleReceiptHeader->customer->address); ?></div>
                </div>
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">TT #</div>
                    <div class="divtablecell info hcolumn2value"><?php //echo CHtml::encode($saleCheque->saleChequeDetails[0]->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Tanggal TT</div>
                    <div class="divtablecell info hcolumn2value"><?php //echo CHtml::encode(CHtml::value($saleCheque->saleChequeDetails[0]->saleReceiptHeader, 'date')); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Giro / Cek #</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(CHtml::value($saleCheque, 'cheque_number')); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Bank</div>
                    <div class="divtablecell info hcolumn2value"><?php //echo CHtml::encode(CHtml::value($saleCheque, 'bank')); ?></div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Jumlah</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($saleCheque, 'amount'))); ?></div>
                </div>-->
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th>Tanda Terima #</th>
        <th>Tanggal</th>
		<th>Jatuh Tempo</th>
        <th>Customer</th>
        <th>Total(Rp)</th>
        <th>Bank</th>
		<th>Cheque Number</th>
		<th>Amount</th>
    </tr>
    <?php foreach ($saleCheque->saleChequeDetails as $i => $detail): ?>
        <tr class="titems">
            <td style="width: 15%"><?php echo CHtml::encode($detail->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></td>
            <td style="width: 15%; text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.date')))); ?></td>
            <td style="width: 15%; text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.due_date')))); ?></td>
			<td style="width: 25%"><?php echo CHtml::encode(CHtml::value($detail, 'saleReceiptHeader.customer.company')); ?></td>
            <td style="width: 15%; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'saleReceiptHeader.totalInvoice'))); ?></td>
            <td style="text-align: right"><?php echo CHtml::encode(CHtml::value($detail, 'bank')); ?></td>
			<td style="text-align: right"><?php echo CHtml::encode(CHtml::value($detail, 'cheque_number')); ?></td>
			<td style="width: 15%; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'amount'))); ?></td>
		</tr>
    <?php endforeach; ?>
    <tr>
        <td colspan ="3" style="border-top: 2px solid;text-align: right;font-weight: bold">Total</td>
        <td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $saleCheque->getTotalSaleReceipt())); ?></td>
        <td colspan = "3" style="border-top: 2px solid"></td>
		<td style="border-top: 2px solid; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $saleCheque->totalAmount)); ?></td>
    </tr>
</table>

<div class="memosig">
    <div class="divtable">
        <div class="divtablecell sig1">
            <div>Diterima Oleh,</div>
        </div>
        <div class="divtablecell sig3">
            <div>Diperiksa oleh,</div>
        </div>
    </div>
</div>