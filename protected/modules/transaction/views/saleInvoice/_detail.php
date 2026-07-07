<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center">Nama Barang</th>
        <th style="text-align: center">Jumlah</th>
        <th style="text-align: center">Satuan</th>
        <th style="text-align: center">Harga Satuan</th>
        <th style="text-align: center">Total</th>
    </tr>
    
    <?php $saleHeader = ($delivery->saleHeader === null) ? SaleHeader::model() : $delivery->saleHeader; ?>
    <?php foreach ($delivery->deliveryDetails as $detail): ?>
        <?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>

        <tr style="background-color: azure">
            <td style="width: auto">
                <?php echo CHtml::encode($detail->getProductName($delivery->sale_header_id)); ?>
            </td>
            <td style="text-align: center; width: 10%">
                <?php echo CHtml::encode(CHtml::value($detail, 'quantity')); ?>
            </td>
            <td style="text-align: center; width: 5%">
                <?php echo CHtml::encode($detail->getProductUnit($delivery->sale_header_id)); ?>
            </td>
            <td style="text-align: right; width: 15%">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getUnitPrice($delivery->sale_header_id))); ?>
            </td>
            <td style="text-align: right; width: 15%">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getTotal($delivery->sale_header_id))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr style="background-color: aquamarine">
        <td colspan="4" style="text-align: right">Sub Total:</td>
        <td style="text-align: right">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $delivery->subTotal)); ?>
        </td>
    </tr>
    
    <tr style="background-color: aquamarine">
        <td colspan="4" style="text-align: right">
            Diskon:
        </td>
        <td style="text-align: right">
            <?php echo CHtml::activeTextField($saleInvoice->header, 'discount', array(
                'size' => 7, 
                'maxlength' => 18,
                'onchange' => CHtml::ajax(array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'url' => CController::createUrl('ajaxJsonGrandTotal', array('id' => $saleInvoice->header->id)),
                    'success' => 'function(data) {
                        $("#tax").html(data.tax);
                        $("#grand_total").html(data.grandTotal);
                    }',
                ))
            )); ?>
        </td>
    </tr>
    
    <tr style="background-color: aquamarine">
        <td colspan="4" style="text-align: right">Ongkos Kirim:</td>
        <td style="text-align: right">
            <?php echo CHtml::activeTextField($saleInvoice->header, 'shipping_fee', array(
                'size' => 7, 
                'maxLength' => 20,
                'onchange' => CHtml::ajax(array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'url' => CController::createUrl('ajaxJsonGrandTotal', array('id' => $saleInvoice->header->id)),
                    'success' => 'function(data) {
                        $("#tax").html(data.tax);
                        $("#grand_total").html(data.grandTotal);
                    }',
                )),
            )); ?>
        </td>
    </tr>
    
    <tr style="background-color: aquamarine">
        <td colspan="4" style="text-align: right">
            PPN 
            <?php echo CHtml::activeTextField($saleInvoice->header, 'tax_percentage', array(
                'size' => 1, 
                'maxlength' => 2,
                'onchange' => CHtml::ajax(array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'url' => CController::createUrl('ajaxJsonCodeNumberTaxTotal', array('id' => $saleInvoice->header->id)),
                    'success' => 'function(data) {
                        $("#taxValue").html(data.taxValue);
                        $("#grand_total").html(data.grandTotal);
                    }',
                )),
            )); ?>%:
            <?php //echo CHtml::encode(CHtml::value($saleHeader, 'tax')); ?>
        </td>
        <td style="text-align: right">
            <span id="tax">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleInvoice->header->calculatedTax)); ?>
            </span>
        </td>
    </tr>
    
    <tr style="background-color: aquamarine">
        <td  colspan="4" style="font-weight: bold; text-align: right">Grand Total:</td>
        <td style="font-weight: bold; text-align: right">
            <span id="grand_total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleInvoice->header->grandTotal)); ?>
            </span>
        </td>
    </tr>
</table>