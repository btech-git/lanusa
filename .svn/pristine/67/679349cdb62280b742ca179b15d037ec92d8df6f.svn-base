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

    .hcolumn1header { width: 25% }
    .hcolumn1value { width: 75% }
    .hcolumn2header { width: 35% }
    .hcolumn2value { width: 65% }

    .sig1 { width: 25% }
    .sig2 { width: 50% }
    .sig3 { width: 25% }
');
?>

<div id="memoheader">
	<?php if ($purchaseReceipt->branch_id != 4): ?>
		<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
		<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
	<?php endif; ?>
	<div style="font-size: larger">Tanda Terima</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
				<div class="divtable">
					<div class="divtablerow">
						<div class="divtablecell info hcolumn1header">Sudah terima dari</div>
						<div class="divtablecell info hcolumn1value">:<?php echo CHtml::encode(CHtml::value($supplier, 'company')); ?></div>
					</div><br/><br/>
					<div class="divtablerow">
						<div class="divtablecell info hcolumn1header">Berupa Nota - nota sebanyak</div>
						<div class="divtablecell info hcolumn1value">:.......... Lembar</div>
					</div><br/><br/>
					<div class="divtablerow">
						<div class="divtablecell info hcolumn1header">Dengan Perincian</div>
						<div class="divtablecell info hcolumn1value">:</div>
					</div><br/>
				</div>
            </div>
        </div>
    </div>
</div>

<table border="0" style="width:800px; margin-left:100px">
    <?php foreach ($purchaseReceiptDetails as $i => $detail): ?>
        <tr class="titems">
			<td style="text-align: center; font-size:12px; width:auto"><?php echo $i + 1; ?>.</td>
			<td>Tgl</td>
			<td style="text-align: center; width:auto"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'receiveHeader.date')))); ?></td>
			<td style="width:auto"></td>
			<td style="width:auto"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
			<td style="width:auto;padding-right: 20px">Jumlah</td>
			<td style="width:auto;padding-right: 20px">Rp.</td>
			<td style="text-align: right; width:auto;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'receiveHeader.totalPurchase'))); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="5" style="text-align: right"></td>
		<td style="text-align: left;border-top: 2px solid;">Total</td>
		<td style="text-align: left;border-top: 2px solid;">Rp.</td>
        <td style="border-top: 2px solid;text-align: right; width:auto"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', floor(CHtml::value($purchaseReceipt, 'totalReceivePrice')))); ?></td>

    </tr>
	<tr>
        <td colspan="5"></td>
		<td style="border-top: 2px solid"></td>
        <td style="border-top: 2px solid"></td>
        <td style="border-top: 2px solid"></td>
    </tr>
</table>

<div style="float:left; margin-left: 100px">Kembali tgl. ....................</div>
<div style="margin-left: 600px">
	Jakarta,...............................................................<br/><br/>
	<div style="text-align: center"> Hormat kami,</div>
</div>
<br/>
<div style="font-weight: bold">
	TANDA TERIMA SETIAP RABU & KAMIS<br/>
	JAM: 14.00 s/d 16.00<br/><br/>
	TAGIHAN SETIAP SELASA<br/>
	JAM: 13.30 s/d 16.30<br/>
</div>

