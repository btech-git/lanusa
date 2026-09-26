<?php echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL; ?>
<TaxInvoiceBulk xmlns:xsd="http://w3.org" xmlns:xsi="http://w3.org-instance">
    <TIN><?php echo CHtml::value($saleInvoiceHeaders[0], 'branch.npwp'); ?></TIN>
    <ListOfTaxInvoice>
        <?php foreach ($saleInvoiceHeaders as $saleInvoiceHeader): 
            // Resolve safe paths to variables to protect against crashes
            $saleHeader = CHtml::value($saleInvoiceHeader, 'deliveryHeader.saleHeader');
            $customer = CHtml::value($saleHeader, 'customer');
        ?>
            <TaxInvoice>
                <TaxInvoiceDate><?php echo CHtml::value($saleInvoiceHeader, 'date'); ?></TaxInvoiceDate>
                <TaxInvoiceOpt>Normal</TaxInvoiceOpt>
                <TrxCode>04</TrxCode>
                <AddInfo/>
                <CustomDoc/>
                <RefDesc><?php echo $saleInvoiceHeader->getCodeNumber(SaleInvoice::CN_CONSTANT); ?></RefDesc>
                <FacilityStamp/>
                <SellerIDTKU><?php echo CHtml::value($saleInvoiceHeader, 'branch.npwp'); ?>000000</SellerIDTKU>
                <BuyerTin><?php echo CHtml::value($customer, 'npwp'); ?></BuyerTin>
                <BuyerDocument>TIN</BuyerDocument>
                <BuyerCountry>IDN</BuyerCountry>
                <BuyerDocumentNumber/>
                <BuyerName><?php echo CHtml::value($customer, 'company'); ?></BuyerName>
                <BuyerAdress><?php echo htmlspecialchars(CHtml::value($customer, 'address', ''), ENT_XML1); ?></BuyerAdress>
                <BuyerEmail><?php echo CHtml::value($customer, 'email'); ?></BuyerEmail>
                <BuyerIDTKU><?php echo CHtml::value($customer, 'npwp'); ?>000000</BuyerIDTKU>
                <ListOfGoodService>
                    <?php foreach (CHtml::value($saleInvoiceHeader, 'deliveryHeader.deliveryDetails', []) as $saleInvoiceDetail): ?>
                        <GoodService>
                            <Opt>A</Opt>
                            <Code>720000</Code>
                            <Name><?php echo htmlspecialchars(CHtml::value($saleInvoiceDetail, 'saleDetail.product_name', ''), ENT_XML1); ?> - <?php echo htmlspecialchars(CHtml::value($saleInvoiceDetail, 'saleDetail.product.size', ''), ENT_XML1); ?></Name>
                            <Unit>UM.0021</Unit>
                            <Price><?php echo CHtml::value($saleInvoiceDetail, 'saleDetail.unit_price'); ?></Price>
                            <Qty><?php echo CHtml::value($saleInvoiceDetail, 'quantity'); ?></Qty>
                            <TotalDiscount>0.00</TotalDiscount>
                            <TaxBase><?php echo CHtml::value($saleInvoiceDetail, 'total'); ?></TaxBase>
                            <OtherTaxBase><?php echo CHtml::value($saleInvoiceDetail, 'totalWithCoretax', 0); ?></OtherTaxBase>
                            <VATRate>12</VATRate>
                            <VAT><?php echo CHtml::value($saleInvoiceDetail, 'totalWithTax', 0); ?></VAT>
                            <STLGRate>0</STLGRate>
                            <STLG>0.00</STLG>
                        </GoodService>
                    <?php endforeach; ?>
                </ListOfGoodService>
            </TaxInvoice>
        <?php endforeach; ?>
    </ListOfTaxInvoice>
</TaxInvoiceBulk>
