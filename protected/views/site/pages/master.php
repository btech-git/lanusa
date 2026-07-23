<?php
$this->pageTitle = Yii::app()->name . ' - Lanusa';
$this->breadcrumbs = array(
    'Lanusa',
);
?>
<h1>Halaman Master</h1>

<div class="form">        
    <fieldset>
        <legend>Data Master</legend>
        <ul style="display:  table-cell; width: 800px">
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Produk', array('/admin/product/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Pelanggan', array('/admin/customer/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Supplier', array('/admin/supplier/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Gudang', array('/admin/warehouse/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Chart of Account', array('/admin/account/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Account Category', array('/admin/accountCategory/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Account Category Type', array('/admin/accountCategoryType/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Payment Type', array('/admin/paymentType/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Perusahaan', array('/admin/branch/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Data Employee', array('/admin/employee/admin')); ?></li>
        </ul>
    </fieldset>

    <fieldset>
        <legend>Data Pembantu</legend>
        <ul style="display: table-cell; width: 800px">
            <li class="left" style="width: 50%"><?php echo CHtml::link('Kategori Produk', array('/admin/category/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Specification', array('/admin/categorySpecification/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Connection Material', array('/admin/connectionMaterial/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Material Grade Brand', array('/admin/categoryMaterialGradeBrand/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Connection', array('/admin/connection/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Material', array('/admin/categoryMaterial/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Material', array('/admin/material/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Connection', array('/admin/categoryConnection/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Ketebalan', array('/admin/thickness/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Classification Material', array('/admin/categoryClassificationMaterial/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Bellow', array('/admin/bellow/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Classification', array('/admin/categoryClassification/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Class', array('/admin/classification/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Type', array('/admin/categoryType/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Brand Kategori', array('/admin/categoryBrand/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Brand Body', array('/admin/categoryBrandBody/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Specification', array('/admin/specification/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Brand Disc', array('/admin/categoryBrandDisc/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Parameter', array('/admin/parameter/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Brand Type', array('/admin/categoryBrandType/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Merk', array('/admin/brand/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Classification Connection', array('/admin/categoryClassificationConnection/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Disc Material', array('/admin/discMaterial/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Classification Variety', array('/admin/categoryClassificationVariety/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Jenis', array('/admin/variety/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Grade', array('/admin/categoryGrade/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Handling', array('/admin/handling/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Material Grade', array('/admin/categoryMaterialGrade/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Tipe', array('/admin/type/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Material Grade Thickness', array('/admin/categoryMaterialGradeThickness/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Grade', array('/admin/grade/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Thickness', array('/admin/categoryThickness/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Range', array('/admin/range/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Variety', array('/admin/categoryVariety/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Body Type', array('/admin/bodyType/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Brand Connection', array('/admin/categoryBrandConnection/admin')); ?><br/><br/></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Satuan', array('/admin/unit/admin')); ?></li>
            <li class="left" style="width: 50%"><?php echo CHtml::link('Category Brand Handling', array('/admin/categoryBrandHandling/admin')); ?><br/><br/></li>
        </ul>
    </fieldset>
</div>
