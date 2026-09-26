<?xml version="1.0" encoding="utf-8"?>
<TaxInvoiceBulk xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <TIN><?php echo $saleInvoiceHeaders[0]->branch->npwp; ?></TIN>
    <ListOfTaxInvoice>
<?php foreach ($saleInvoiceHeaders as $saleInvoiceHeader): ?>
        <TaxInvoice>
            <TaxInvoiceDate><?php echo CHtml::value($saleInvoiceHeader, 'date'); ?></TaxInvoiceDate>
            <TaxInvoiceOpt>Normal</TaxInvoiceOpt>
            <TrxCode>04</TrxCode>
            <AddInfo/>
            <CustomDoc/>
            <RefDesc><?php echo $saleInvoiceHeader->getCodeNumber(SaleInvoice::CN_CONSTANT); ?></RefDesc>
            <FacilityStamp/>
            <SellerIDTKU><?php echo CHtml::value($saleInvoiceHeader, 'branch.npwp'); ?>000000</SellerIDTKU>
            <BuyerTin><?php echo CHtml::value($saleInvoiceHeader, 'deliveryHeader.saleHeader.customer.npwp'); ?></BuyerTin>
            <BuyerDocument>TIN</BuyerDocument>
            <BuyerCountry>IDN</BuyerCountry>
            <BuyerDocumentNumber/>
            <BuyerName><?php echo CHtml::value($saleInvoiceHeader, 'customer.company'); ?></BuyerName>
            <BuyerAdress><?php echo htmlspecialchars(CHtml::value($saleInvoiceHeader, 'customer.address'), ENT_XML1); ?></BuyerAdress>
            <BuyerEmail><?php echo CHtml::value($saleInvoiceHeader, 'customer.email'); ?></BuyerEmail>
            <BuyerIDTKU><?php echo CHtml::value($saleInvoiceHeader, 'deliveryHeader.saleHeader.customer.npwp'); ?>000000</BuyerIDTKU>
            <ListOfGoodService>
                <?php foreach ($saleInvoiceHeader->deliveryHeader->deliveryDetails as $saleInvoiceDetail): ?>
                    <GoodService>
                        <Opt>A</Opt>
                        <Code>720000</Code>
                        <Name><?php echo CHtml::value($saleInvoiceDetail, 'saleDetail.product_name'); ?> - <?php echo CHtml::value($saleInvoiceDetail, 'saleDetail.product.size'); ?></Name>
                        <Unit>UM.0021</Unit>
                        <Price><?php echo CHtml::value($saleInvoiceDetail, 'saleDetail.unit_price'); ?></Price>
                        <Qty><?php echo CHtml::value($saleInvoiceDetail, 'quantity'); ?></Qty>
                        <TotalDiscount>0.00</TotalDiscount>
                        <TaxBase><?php echo CHtml::value($saleInvoiceDetail, 'total'); ?></TaxBase>
                        <OtherTaxBase><?php echo CHtml::value($saleInvoiceDetail, 'totalWithCoretax'); ?></OtherTaxBase>
                        <VATRate>12</VATRate>
                        <VAT><?php echo CHtml::value($saleInvoiceDetail, 'totalWithTax'); ?></VAT>
                        <STLGRate>0</STLGRate>
                        <STLG>0.00</STLG>
                    </GoodService>
                <?php endforeach; ?>
            </ListOfGoodService>
        </TaxInvoice>
<?php endforeach; ?>
    </ListOfTaxInvoice>
</TaxInvoiceBulk>
