<div class="form">
    <div class="container">
        <fieldset>
            <legend>Giro Belum Cair</legend>
            <div class="row">
                <table style="border: 1px solid">
                    <thead>
                        <tr style="background-color: skyblue">
                            <td style="width: 10%">Transaksi #</td>
                            <td style="width: 10%">Tanggal</td>
                            <td style="width: 10%">TT #</td>
                            <td>Supplier</td>
                            <td style="width: 15%">Hutang</td>
                            <td style="width: 20%">Note</td>
                            <td style="width: 10%">Status</td>
                            <td style="width: 5%"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchasePaymentData as $purchasePayment): ?>
                            <tr style="background-color: azure">
                                <td><?php echo CHtml::encode($purchasePayment->getCodeNumber(PurchasePaymentHeader::CN_CONSTANT)); ?></td>
                                <td><?php echo CHtml::encode(Yii::app()->dateFormatter->format("d MMM yyyy", CHtml::value($purchasePayment, 'date'))); ?></td>
                                <td><?php echo CHtml::encode($purchasePayment->purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?></td>
                                <td><?php echo CHtml::encode(CHtml::value($purchasePayment, 'purchaseReceiptHeader.supplier.name')); ?></td>
                                <td style="text-align: right;">
                                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchasePayment, 'purchaseReceiptHeader.grand_total'))); ?>
                                </td>
                                <td><?php echo CHtml::encode(CHtml::value($purchasePayment, 'note')); ?></td>
                                <td><?php echo CHtml::encode(CHtml::value($purchasePayment, 'status')); ?></td>
                                <td><?php echo CHtml::link('Cair', array("completePayment", "id" => $purchasePayment->id), array('confirm' => 'Are you sure you want to complete transaction?')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>