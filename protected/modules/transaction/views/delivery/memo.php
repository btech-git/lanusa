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
        
        .hcolumn1header { width: 30% }
		.hcolumn1value { width: 70% }
        
        .sig1 { width: 20% }
        .sig2 { width: 25% }
		
		.hcolumn1memoheader { width: 70% }
		.hcolumn2memoheader { width: 30% }
');
?>

<div id="memoheader">
	<div class="divtable">
		<div class="divtablecell hcolumn1memoheader">
			<div class="divtable">
				<div class="divtablerow">
					<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
					<div style="font-size: 16px"><?php echo CHtml::encode(CHtml::value($branch, 'province')); ?></div><br/>
				</div>
				
			</div>
		</div>
		
		<div class="divtablecell hcolumn2memoheader">
			<div class="divtable">
				<div class="divtablerow" style="text-align: left; font-weight: 200;">
					<div>Jakarta, <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($delivery, 'date')))); ?></div>
				</div>
				<div class="divtablerow" style="text-align: left; font-weight: 200;">
					<div style="font-weight: bold">Kepada,</div>
					<div><?php echo CHtml::encode(CHtml::value($customer, 'company')); ?></div>
					<div><?php echo nl2br(CHtml::encode(CHtml::value($customer, 'address'))); ?></div>
				</div>
		</div>
	</div>
</div>

<br />

<div class="memonote" style="width:100%">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">SURAT JALAN No : </div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($delivery->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></div>
                </div>
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($delivery, 'date')))); ?></div>
                </div>-->
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">N.I.K</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(CHtml::value($delivery, 'plate_number')); ?></div>
                </div>-->
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Pengirim</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(CHtml::value($delivery, 'driver')); ?></div>
                </div>-->
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
<!--                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Dibuat oleh</div>
                    <div class="divtablecell info hcolumn1value"><?php //echo CHtml::encode(CHtml::value($admin, 'name')); ?></div>
                </div>-->
                <div class="divtablerow">
					<div class="divtablecell info hcolumn1header" style="font-weight: bold">PO:</div>
					<div class="divtablecell info hcolumn1value"><?php echo CHtml::encode($delivery->saleHeader->reference); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
		<th colspan="2">Banyaknya</th>
        <th>NAMA BARANG</th>
        
    </tr>
    <?php $i = 0; ?>
    <?php foreach ($deliveryDetails as $i => $detail): ?>
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope','with'=>'unit:resetScope')); ?>
        <tr class="titems">
			<td style="text-align: center; width: 10%; border-right: none"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', (CHtml::value($detail, 'quantity')))); ?></td>
            <td style="text-align: center; width: 10%; border-left: none"><?php echo CHtml::encode($detail->getProductUnit($delivery->sale_header_id)); ?></td>
            <td><?php echo CHtml::encode($detail->getProductName()); ?></td>
           
        </tr>
    <?php endforeach; ?>
    <?php for ($j = 12, $i = $i % $j + 1; $j > $i; $j--): ?>
        <tr class="titems">
            <td style="border-right: none">&nbsp;</td>
            <td style="border-left: none">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    <?php endfor; ?>

</table>

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
