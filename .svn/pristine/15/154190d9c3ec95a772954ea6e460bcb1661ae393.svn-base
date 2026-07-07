<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 65% }
	.width1-3 { width: 15% }

	.width2-1 { width: 15% }
	.width2-2 { width: 15% }
	.width2-3 { width: 20% }
	.width2-4 { width: 10% }
	.width2-5 { width: 15% }
	.width2-6 { width: 10% }
	.width2-7 { width: 15% }
');

//start date and end date are empty and hide all the detail
$startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
$endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Pembelian Barang berdasarkan Produk</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Kategori</th>
		<th class="width1-2">Nama Produk</th>
		<th class="width1-3">Ukuran</th>
	</tr>
    <tr id="header2">
        <td colspan="6">
            <table>
                <tr>
                    <th class="width2-1">Pembelian #</th>
                    <th class="width2-2">Tanggal</th>
                    <th class="width2-3">Supplier</th>
                    <th class="width2-4">Jumlah</th>
                    <th class="width2-5">Harga</th>
                    <th class="width2-6">Disc</th>
                    <th class="width2-7">Total</th>
                </tr>
            </table>
        </td>
    </tr>
    
    <?php foreach ($purchaseItemSummary->dataProvider->data as $header): ?>
        <tr class="items1">
            <td class="width1-1" style="text-align:center"><?php echo CHtml::encode(CHtml::value($header, 'category.name')); ?></td>
            <td class="width1-2" style="text-align:center"><?php echo CHtml::encode(CHtml::value($header, 'name')); ?></td>
            <td class="width1-3" style="text-align:center"><?php echo CHtml::encode(CHtml::value($header, 'size')); ?></td>
        </tr>
        
        <tr class="items2">
            <td colspan="6">
                <table>
                    <?php $total = 0; $quantityTotal = 0; ?>
                    <?php foreach ($header->purchaseDetails as $detail): ?>
                        <?php if ($detail->purchaseHeader !== null 
                            && $detail->purchaseHeader->date >= $startDate
                            && $detail->purchaseHeader->date <= $endDate
                            && $detail->purchaseHeader->branch_id == $branch->id
                        ):	//relation doesn't filter the attributes from filter?>
                        <tr>
                            <td class="width2-1" style="text-align: center">
                                <?php echo ($detail->purchaseHeader !== null)
                                    ? CHtml::encode($detail->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT))
                                    : 'No Purchase Header'; ?>
                            </td>
                            <td class="width2-2" style="text-align:center">
                                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'purchaseHeader.date')))); ?>
                            </td>
                            <td class="width2-3" style="text-align:center"><?php echo CHtml::encode(CHtml::value($detail, 'purchaseHeader.supplier.company')); ?></td>														
                            <td class="width2-4" style="text-align: center"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity'))); ?></td>
                            <td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unitPrice'))); ?></td>
                            <td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'discount'))); ?></td>
                            <td class="width2-7" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'totalReport'))); ?></td>
                        </tr>
                        <?php $quantityTotal += CHtml::value($detail, 'quantity'); ?>
                        <?php $total += CHtml::value($detail, 'totalReport');?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="border-top: 0px solid; font-weight: bold; font-size: small; text-align: right;">TOTAL</td>
                        <td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: center; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($quantityTotal))); ?></td>
                        <td colspan="3" style="border-top: 1px solid; font-weight: bold; text-align: right; font-size: small"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($total))); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endforeach; ?>
</table>