<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 3% }
	.width1-2 { width: 50% }
	.width1-3 { width: 10% }
	.width1-4 { width: 10% }
	.width1-5 { width: 5% }
	.width1-6 { width: 5% }
	.width1-7 { width: 10% }
');
?>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger">PT Logam Nusantara</div>
    <div style="font-size: larger">Fast Moving Products</div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
    <tr id="header1">
        <th class="width1-1">No</th>
        <th class="width1-2">Product</th>
        <th class="width1-3">Size</th>
        <th class="width1-4">Category</th>
        <th class="width1-6">Quantity</th>
        <th class="width1-5">Satuan</th>
        <th class="width1-7">Amount</th>
    </tr>
    <tr id="header2">
        <td colspan="7">&nbsp;</td>
    </tr>
        <?php $fastMovingItems = $deliveryDetail->getFastMovingProducts($startDate, $endDate); ?>
        <?php foreach ($fastMovingItems as $i => $fastMovingItem): ?>
            <tr class="items1">
                <td style="text-align: center"><?php echo CHtml::encode($i + 1); ?></td>
                <td><?php echo CHtml::encode($fastMovingItem['product_name']); ?></td>
                <td><?php echo CHtml::encode($fastMovingItem['size']); ?></td>
                <td><?php echo CHtml::encode($fastMovingItem['category']); ?></td>
                <td style="text-align: center">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $fastMovingItem['total_sale'])); ?>
                </td>
                <td><?php echo CHtml::encode($fastMovingItem['unit_name']); ?></td>
                <td style="text-align: right">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $fastMovingItem['sale_price'])); ?>
                </td>
            </tr>
            <tr class="items2">
                <td colspan="7">
            </td>
        </tr>
    <?php endforeach; ?>
</table>