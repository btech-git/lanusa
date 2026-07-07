<?php
Yii::app()->clientScript->registerScript('memo', '
        $("#header").addClass("hide");
        $("#mainmenu").addClass("hide");
        $(".breadcrumbs").addClass("hide");
        $("#footer").addClass("hide");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/memo.css');
Yii::app()->clientScript->registerCss('memo', '
        div.memonote {
            width: 100%;
            margin: 0 auto;
        }
    
        .hcolumn1 { width: 50% }
        .hcolumn2 { width: 50% }
        
        .hcolumn1header { width: 35% }
        .hcolumn1value { width: 65% }
        .hcolumn2header { width: 35% }
        .hcolumn2value { width: 65% }
        
        .width-1 { width: 40% }
	.width-2 { width: 20% }
	.width-3 { width: 40% }
        
        .sig1 { width: 20% }
        .sig2 { width: 20% }
        .sig3 { width: 20% }
        .sig4 { width: 40% }
        
        @page {
            size:auto;
            margin: 10px 10px 10px 10px !important;
        }
        
        #page {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 10px !important;
            margin-right: 10px !important;
          
        }
');
?>

<div id="memoheader">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger"> Bukti Penerimaan Kas/Bank</div>
</div>

<br />

<div class="memonote">
    <div class="divtable">
        <div class="divtablecell hcolumn1">
            <div class="divtable">
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Bukti #</div>
                    <div class="divtablecell info hcolumn1value">
                        <?php
                        $depositHeaderConstant;
                        if ($deposit->is_bank) {
                            $depositHeaderConstant = DepositHeader::CN_CONSTANT_BANK;
                        } else {
                            $depositHeaderConstant = DepositHeader::CN_CONSTANT_CASH;
                        }
                        echo CHtml::encode($deposit->getCodeNumber($depositHeaderConstant));
                        ?>




                    </div>
                </div>
                <div class="divtablerow">
                    <div class="divtablecell info hcolumn1header" style="font-weight: bold">Tanggal</div>
                    <div class="divtablecell info hcolumn1value"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($deposit, 'date')))); ?></div>
                </div>
				<div class="divtablerow">
                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Account</div>
                    <div class="divtablecell info hcolumn2value"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></div>
                </div>
            </div>
        </div>
        <div class="divtablecell hcolumn2">
            <div class="divtable">
                
                <div class="divtablerow">
<!--                    <div class="divtablecell info hcolumn2header" style="font-weight: bold">Catatan</div>
                    <div class="divtablecell info hcolumn2value"><?php //echo CHtml::encode(CHtml::value($deposit, 'note')); ?></div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<table class="memo">
    <tr id="theader">
        <th class="width-1">Memo</th>
        <th class="width-2">Jumlah (Rp.)</th>
        <th class="width-3">Keterangan</th>

    </tr>
<?php foreach ($depositDetails as $i => $detail): ?>
        <tr class="titems">
            <td class="width-1"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
            <td class="width-2" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'amount'))); ?></td>
            <td class="width-3"><?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?></td>
        </tr>
<?php endforeach; ?>
    <?php for ($j = 8, $i = $i % $j + 1; $j > $i; $j--): ?>
        <tr class="titems">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
<?php endfor; ?>
    <tr>
        <td style="border-top: 2px solid; border-left: 1px solid; font-weight: bold; text-align: right">Total</td>
        <td style="border-top: 2px solid; font-weight: bold; text-align: right; border-right: 1px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor(CHtml::value($deposit, 'total')))); ?></td>
        <td style="border-top: 2px solid; border-right: 1px solid;border-left: 1px solid;"></td>
    </tr>

</table>

<div style="text-transform: capitalize">
    Terbilang:
<?php echo CHtml::encode(NumberWord::numberName(floor(CHtml::value($deposit, 'total')))); ?>
    rupiah
</div>

<br />

<div class="memosig">
    <div style="font-weight:bold; border-left:2px solid; border-right:2px solid; border-bottom:2px solid; border-top:2px solid" class="divtable">
        <div style="border-right:2px solid" class="divtablecell sig1">
            <div>Dibuat,</div>
            <div style="border-top:2px solid"><br/><br/><br/><br/><?php echo CHtml::encode(CHtml::value($deposit, 'admin.name')); ?></div>
        </div>
        <div style="border-right:2px solid" class="divtablecell sig2">
            <div>Diperiksa,</div>
            <div style="border-top:2px solid"></div>
        </div>
        <div style="border-right:2px solid"  class="divtablecell sig3">
            <div>Disetujui,</div>
            <div style="border-top:2px solid"></div>
        </div>
        <div class="divtablecell sig4">
            <div>Diterima,</div>
            <div style="border-top:2px solid"></div>
        </div>
    </div>
</div>
</div>